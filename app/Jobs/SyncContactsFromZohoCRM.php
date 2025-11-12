<?php

namespace App\Jobs;

use App\Models\CrmContact;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncContactsFromZohoCRM implements ShouldQueue
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

        Log::info('Starting contacts synchronization from Zoho CRM...');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->crm->getContacts([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoContacts = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoContacts as $zohoContact) {
                    $this->syncContact($zohoContact);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for contacts sync');
                    break;
                }
            }

            Log::info("Contacts sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing contacts from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncContact(array $zohoContact): void
    {
        DB::beginTransaction();

        try {
            $localContact = CrmContact::where('zoho_contact_id', $zohoContact['id'])->first();

            $contactData = [
                'zoho_contact_id' => $zohoContact['id'],
                'first_name' => $zohoContact['First_Name'] ?? null,
                'last_name' => $zohoContact['Last_Name'] ?? 'Unknown',
                'full_name' => $zohoContact['Full_Name'] ?? null,
                'salutation' => $zohoContact['Salutation'] ?? null,
                'title' => $zohoContact['Title'] ?? null,
                'department' => $zohoContact['Department'] ?? null,
                'email' => $zohoContact['Email'] ?? null,
                'secondary_email' => $zohoContact['Secondary_Email'] ?? null,
                'phone' => $zohoContact['Phone'] ?? null,
                'mobile' => $zohoContact['Mobile'] ?? null,
                'home_phone' => $zohoContact['Home_Phone'] ?? null,
                'other_phone' => $zohoContact['Other_Phone'] ?? null,
                'fax' => $zohoContact['Fax'] ?? null,
                'assistant' => $zohoContact['Assistant'] ?? null,
                'assistant_phone' => $zohoContact['Asst_Phone'] ?? null,
                'mailing_street' => $zohoContact['Mailing_Street'] ?? null,
                'mailing_city' => $zohoContact['Mailing_City'] ?? null,
                'mailing_state' => $zohoContact['Mailing_State'] ?? null,
                'mailing_zip' => $zohoContact['Mailing_Zip'] ?? null,
                'mailing_country' => $zohoContact['Mailing_Country'] ?? null,
                'other_street' => $zohoContact['Other_Street'] ?? null,
                'other_city' => $zohoContact['Other_City'] ?? null,
                'other_state' => $zohoContact['Other_State'] ?? null,
                'other_zip' => $zohoContact['Other_Zip'] ?? null,
                'other_country' => $zohoContact['Other_Country'] ?? null,
                'account_id' => $zohoContact['Account_Name']['id'] ?? null,
                'account_name' => $zohoContact['Account_Name']['name'] ?? null,
                'vendor_name' => $zohoContact['Vendor_Name']['name'] ?? null,
                'lead_source' => $zohoContact['Lead_Source'] ?? null,
                'date_of_birth' => isset($zohoContact['Date_of_Birth']) ? date('Y-m-d', strtotime($zohoContact['Date_of_Birth'])) : null,
                'owner_id' => $zohoContact['Owner']['id'] ?? null,
                'owner_name' => $zohoContact['Owner']['name'] ?? null,
                'twitter' => $zohoContact['Twitter'] ?? null,
                'skype_id' => $zohoContact['Skype_ID'] ?? null,
                'description' => $zohoContact['Description'] ?? null,
                'email_opt_out' => $zohoContact['Email_Opt_Out'] ?? false,
                'reporting_to' => $zohoContact['Reporting_To']['id'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localContact) {
                $localContact->update($contactData);
                $this->updatedCount++;
                Log::info("Updated contact: {$contactData['full_name']} (ID: {$zohoContact['id']})");
            } else {
                CrmContact::create($contactData);
                $this->syncedCount++;
                Log::info("Created new contact: {$contactData['full_name']} (ID: {$zohoContact['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing contact {$zohoContact['id']}: " . $e->getMessage());
        }
    }
}
