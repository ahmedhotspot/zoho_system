<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SyncCompaniesFromAPI implements ShouldQueue
{
    use Queueable;

    protected $syncedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;
    protected $apiUrl = 'https://hotspotloans.co/hotspot/public/api/financingcompanies';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting companies sync from API');

        try {
            // Get all companies from API
            $response = Http::timeout(30)->get($this->apiUrl);

            if (!$response->successful()) {
                throw new \Exception('API request failed with status: ' . $response->status());
            }

            $apiCompanies = $response->json();

            if (!is_array($apiCompanies)) {
                throw new \Exception('Invalid API response format');
            }

            Log::info('Found ' . count($apiCompanies) . ' companies in API');

            foreach ($apiCompanies as $apiCompany) {
                try {
                    $this->syncCompany($apiCompany);
                } catch (\Exception $e) {
                    $this->errorCount++;
                    Log::error('Error syncing company: ' . $e->getMessage(), [
                        'user_id' => $apiCompany['user_id'] ?? 'unknown',
                        'company_name' => $apiCompany['company_name'] ?? 'unknown',
                    ]);
                }
            }

            Log::info('Companies sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting companies sync: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single company
     */
    protected function syncCompany(array $apiCompany): void
    {
        DB::beginTransaction();

        try {
            // Validate required fields
            if (empty($apiCompany['user_id']) || empty($apiCompany['company_name'])) {
                throw new \Exception('Missing required fields: user_id or company_name');
            }

            // Find existing company by user_id
            $company = Company::where('user_id', $apiCompany['user_id'])->first();

            if ($company) {
                // Update existing company
                $company->update([
                    'name' => $apiCompany['company_name'],
                    'is_active' => 1, // Set as active by default
                ]);

                $this->updatedCount++;
                Log::info('Updated company', [
                    'id' => $company->id,
                    'user_id' => $apiCompany['user_id'],
                    'name' => $apiCompany['company_name'],
                ]);
            } else {
                // Create new company
                $company = Company::create([
                    'user_id' => $apiCompany['user_id'],
                    'name' => $apiCompany['company_name'],
                    'is_active' => 1, // Set as active by default
                ]);

                $this->syncedCount++;
                Log::info('Created new company', [
                    'id' => $company->id,
                    'user_id' => $apiCompany['user_id'],
                    'name' => $apiCompany['company_name'],
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in syncCompany: ' . $e->getMessage(), [
                'company_data' => $apiCompany,
            ]);
            throw $e;
        }
    }
}

