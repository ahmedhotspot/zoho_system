<?php

namespace App\Jobs;

use App\Models\CrmLead;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncLeadsFromZohoCRM implements ShouldQueue
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

        Log::info('Starting leads synchronization from Zoho CRM...');

        try {
            // Get last sync time
            $lastSyncTime = CrmLead::max('last_synced_at');

            if ($lastSyncTime) {
                Log::info("Syncing leads modified after: {$lastSyncTime}");
            } else {
                Log::info("First sync - fetching all leads");
            }

            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $params = [
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_by' => 'Modified_Time',
                    'sort_order' => 'desc',
                ];

                // Add If-Modified-Since header if we have a last sync time
                if ($lastSyncTime) {
                    $params['modified_since'] = $lastSyncTime;
                }

                $response = $this->crm->getLeads($params);

                $zohoLeads = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoLeads as $zohoLead) {
                    $this->syncLead($zohoLead);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for leads sync');
                    break;
                }
            }

            Log::info("Leads sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing leads from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncLead(array $zohoLead): void
    {
        DB::beginTransaction();

        try {
            $localLead = CrmLead::where('zoho_lead_id', $zohoLead['id'])->first();

            $leadData = [
                'zoho_lead_id' => $zohoLead['id'],
                'first_name' => $zohoLead['First_Name'] ?? null,
                'last_name' => $zohoLead['Last_Name'] ?? 'Unknown',
                'full_name' => $zohoLead['Full_Name'] ?? null,
                'company' => $zohoLead['Company'] ?? null,
                'title' => $zohoLead['Title'] ?? null,
                'designation' => $zohoLead['Designation'] ?? null,
                'email' => $zohoLead['Email'] ?? null,
                'phone' => $zohoLead['Phone'] ?? null,
                'mobile' => $zohoLead['Mobile'] ?? null,
                'fax' => $zohoLead['Fax'] ?? null,
                'website' => $zohoLead['Website'] ?? null,
                'street' => $zohoLead['Street'] ?? null,
                'city' => $zohoLead['City'] ?? null,
                'state' => $zohoLead['State'] ?? null,
                'zip_code' => $zohoLead['Zip_Code'] ?? null,
                'country' => $zohoLead['Country'] ?? null,
                'lead_status' => $zohoLead['Lead_Status'] ?? 'Not Contacted',
                'lead_source' => $zohoLead['Lead_Source'] ?? null,
                'industry' => $zohoLead['Industry'] ?? null,
                'no_of_employees' => $zohoLead['No_of_Employees'] ?? null,
                'annual_revenue' => $zohoLead['Annual_Revenue'] ?? null,
                'rating' => $zohoLead['Rating'] ?? null,
                'skype_id' => $zohoLead['Skype_ID'] ?? null,
                'twitter' => $zohoLead['Twitter'] ?? null,
                'secondary_email' => $zohoLead['Secondary_Email'] ?? null,
                'description' => $zohoLead['Description'] ?? null,
                'owner_id' => $zohoLead['Owner']['id'] ?? null,
                'owner_name' => $zohoLead['Owner']['name'] ?? null,
                'is_converted' => $zohoLead['Converted'] ?? false,
                'converted_at' => isset($zohoLead['Converted_Date_Time']) ? date('Y-m-d H:i:s', strtotime($zohoLead['Converted_Date_Time'])) : null,
                'converted_contact_id' => $zohoLead['Converted_Contact']['id'] ?? null,
                'converted_account_id' => $zohoLead['Converted_Account']['id'] ?? null,
                'converted_deal_id' => $zohoLead['Converted_Deal']['id'] ?? null,
                'created_by_id' => $zohoLead['Created_By']['id'] ?? null,
                'created_by_name' => $zohoLead['Created_By']['name'] ?? null,
                'modified_by_id' => $zohoLead['Modified_By']['id'] ?? null,
                'modified_by_name' => $zohoLead['Modified_By']['name'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localLead) {
                $localLead->update($leadData);
                $this->updatedCount++;
                Log::info("Updated lead: {$leadData['full_name']} (ID: {$zohoLead['id']})");
            } else {
                CrmLead::create($leadData);
                $this->syncedCount++;
                Log::info("Created new lead: {$leadData['full_name']} (ID: {$zohoLead['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing lead {$zohoLead['id']}: " . $e->getMessage());
        }
    }
}
