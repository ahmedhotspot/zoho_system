<?php

namespace App\Jobs;

use App\Models\CrmAccount;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncAccountsFromZohoCRM implements ShouldQueue
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

        Log::info('Starting accounts synchronization from Zoho CRM...');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->crm->getAccounts([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoAccounts = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoAccounts as $zohoAccount) {
                    $this->syncAccount($zohoAccount);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for accounts sync');
                    break;
                }
            }

            Log::info("Accounts sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing accounts from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncAccount(array $zohoAccount): void
    {
        DB::beginTransaction();

        try {
            $localAccount = CrmAccount::where('zoho_account_id', $zohoAccount['id'])->first();

            $accountData = [
                'zoho_account_id' => $zohoAccount['id'],
                'account_name' => $zohoAccount['Account_Name'] ?? null,
                'account_number' => $zohoAccount['Account_Number'] ?? null,
                'account_type' => $zohoAccount['Account_Type'] ?? null,
                'industry' => $zohoAccount['Industry'] ?? null,
                'annual_revenue' => $zohoAccount['Annual_Revenue'] ?? null,
                'employees' => $zohoAccount['Employees'] ?? null,
                'ownership' => $zohoAccount['Ownership'] ?? null,
                'rating' => $zohoAccount['Rating'] ?? null,
                'sic_code' => $zohoAccount['SIC_Code'] ?? null,
                'ticker_symbol' => $zohoAccount['Ticker_Symbol'] ?? null,
                'phone' => $zohoAccount['Phone'] ?? null,
                'fax' => $zohoAccount['Fax'] ?? null,
                'website' => $zohoAccount['Website'] ?? null,
                'billing_street' => $zohoAccount['Billing_Street'] ?? null,
                'billing_city' => $zohoAccount['Billing_City'] ?? null,
                'billing_state' => $zohoAccount['Billing_State'] ?? null,
                'billing_code' => $zohoAccount['Billing_Code'] ?? null,
                'billing_country' => $zohoAccount['Billing_Country'] ?? null,
                'shipping_street' => $zohoAccount['Shipping_Street'] ?? null,
                'shipping_city' => $zohoAccount['Shipping_City'] ?? null,
                'shipping_state' => $zohoAccount['Shipping_State'] ?? null,
                'shipping_code' => $zohoAccount['Shipping_Code'] ?? null,
                'shipping_country' => $zohoAccount['Shipping_Country'] ?? null,
                'parent_account_id' => $zohoAccount['Parent_Account']['id'] ?? null,
                'parent_account_name' => $zohoAccount['Parent_Account']['name'] ?? null,
                'owner_id' => $zohoAccount['Owner']['id'] ?? null,
                'owner_name' => $zohoAccount['Owner']['name'] ?? null,
                'description' => $zohoAccount['Description'] ?? null,
                'zoho_created_time' => isset($zohoAccount['Created_Time']) ? date('Y-m-d H:i:s', strtotime($zohoAccount['Created_Time'])) : null,
                'zoho_modified_time' => isset($zohoAccount['Modified_Time']) ? date('Y-m-d H:i:s', strtotime($zohoAccount['Modified_Time'])) : null,
                'last_synced_at' => now(),
            ];

            if ($localAccount) {
                $localAccount->update($accountData);
                $this->updatedCount++;
                Log::info("Updated account: {$accountData['account_name']} (ID: {$zohoAccount['id']})");
            } else {
                CrmAccount::create($accountData);
                $this->syncedCount++;
                Log::info("Created new account: {$accountData['account_name']} (ID: {$zohoAccount['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing account {$zohoAccount['id']}: " . $e->getMessage());
        }
    }
}
