<?php

namespace App\Jobs;

use App\Models\CrmDeal;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDealsFromZohoCRM implements ShouldQueue
{
    use Queueable;

    protected $crm;
    protected $syncedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;

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
    public function handle(ZohoCRMService $crm): void
    {
        $this->crm = $crm;

        Log::info('Starting deals synchronization from Zoho CRM...');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->crm->getDeals([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoDeals = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoDeals as $zohoDeal) {
                    $this->syncDeal($zohoDeal);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for deals sync');
                    break;
                }
            }

            Log::info("Deals sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing deals from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncDeal(array $zohoDeal): void
    {
        DB::beginTransaction();

        try {
            $localDeal = CrmDeal::where('zoho_deal_id', $zohoDeal['id'])->first();

            $dealData = [
                'zoho_deal_id' => $zohoDeal['id'],
                'deal_name' => $zohoDeal['Deal_Name'] ?? null,
                'account_id' => $zohoDeal['Account_Name']['id'] ?? null,
                'account_name' => $zohoDeal['Account_Name']['name'] ?? null,
                'contact_id' => $zohoDeal['Contact_Name']['id'] ?? null,
                'contact_name' => $zohoDeal['Contact_Name']['name'] ?? null,
                'stage' => $zohoDeal['Stage'] ?? null,
                'amount' => $zohoDeal['Amount'] ?? null,
                'closing_date' => $zohoDeal['Closing_Date'] ?? null,
                'type' => $zohoDeal['Type'] ?? null,
                'lead_source' => $zohoDeal['Lead_Source'] ?? null,
                'next_step' => $zohoDeal['Next_Step'] ?? null,
                'probability' => $zohoDeal['Probability'] ?? null,
                'expected_revenue' => $zohoDeal['Expected_Revenue'] ?? null,
                'campaign_source' => $zohoDeal['Campaign_Source'] ?? null,
                'owner_id' => $zohoDeal['Owner']['id'] ?? null,
                'owner_name' => $zohoDeal['Owner']['name'] ?? null,
                'description' => $zohoDeal['Description'] ?? null,
                'deal_category_status' => $zohoDeal['Deal_Category_Status'] ?? null,
                'currency' => $zohoDeal['Currency'] ?? 'SAR',
                'exchange_rate' => $zohoDeal['Exchange_Rate'] ?? 1.0000,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localDeal) {
                $localDeal->update($dealData);
                $this->updatedCount++;
                Log::info("Updated deal: {$dealData['deal_name']}");
            } else {
                CrmDeal::create($dealData);
                $this->syncedCount++;
                Log::info("Created new deal: {$dealData['deal_name']}");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing deal: ' . $e->getMessage(), [
                'deal_id' => $zohoDeal['id'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }
}
