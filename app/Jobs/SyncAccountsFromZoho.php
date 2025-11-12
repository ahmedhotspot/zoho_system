<?php

namespace App\Jobs;

use App\Models\Account;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncAccountsFromZoho implements ShouldQueue
{
    use Queueable;

    protected $books;
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
    public function handle(ZohoBooksService $books): void
    {
        $this->books = $books;

        Log::info('Starting account sync from Zoho Books');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->books->getAccounts([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoAccounts = $response['chartofaccounts'] ?? [];
                $pageContext = $response['page_context'] ?? [];

                Log::info("Processing page {$page} with " . count($zohoAccounts) . " accounts");

                foreach ($zohoAccounts as $zohoAccount) {
                    $this->syncAccount($zohoAccount);
                }

                $hasMorePages = $pageContext['has_more_page'] ?? false;
                $page++;
            }

            Log::info('Account sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing accounts from Zoho Books: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single account from Zoho to local database
     */
    protected function syncAccount(array $zohoAccount): void
    {
        DB::beginTransaction();

        try {
            $localAccount = Account::where('zoho_account_id', $zohoAccount['account_id'])->first();

            // Map account type from Zoho to our ENUM
            $accountType = $this->mapAccountType($zohoAccount['account_type'] ?? 'expense');

            $accountData = [
                'zoho_account_id' => $zohoAccount['account_id'],
                'account_name' => $zohoAccount['account_name'],
                'account_code' => $zohoAccount['account_code'] ?? null,
                'account_type' => $accountType,
                'description' => $zohoAccount['description'] ?? null,
                'is_user_created' => $zohoAccount['is_user_created'] ?? false,
                'is_system_account' => $zohoAccount['is_system_account'] ?? false,
                'is_active' => $zohoAccount['is_active'] ?? true,
                'parent_account_id' => $zohoAccount['parent_account_id'] ?? null,
                'parent_account_name' => $zohoAccount['parent_account_name'] ?? null,
                'depth' => $zohoAccount['depth'] ?? 0,
                'currency_code' => $zohoAccount['currency_code'] ?? 'SAR',
                'balance' => $zohoAccount['balance'] ?? 0,
                'bank_balance' => $zohoAccount['bank_balance'] ?? 0,
                'bcy_balance' => $zohoAccount['bcy_balance'] ?? 0,
                'uncategorized_transactions' => $zohoAccount['uncategorized_transactions'] ?? 0,
                'is_involved_in_transaction' => $zohoAccount['is_involved_in_transaction'] ?? false,
                'current_balance' => $zohoAccount['current_balance'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localAccount) {
                $localAccount->update($accountData);
                $this->updatedCount++;
                Log::info("Updated account: {$zohoAccount['account_name']}");
            } else {
                Account::create($accountData);
                $this->syncedCount++;
                Log::info("Created account: {$zohoAccount['account_name']}");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing account: ' . $e->getMessage(), [
                'account_id' => $zohoAccount['account_id'] ?? 'unknown',
                'account_name' => $zohoAccount['account_name'] ?? 'unknown',
            ]);
        }
    }

    /**
     * Map Zoho account type to our ENUM values
     */
    protected function mapAccountType(string $zohoType): string
    {
        $typeMap = [
            'other_asset' => 'other_asset',
            'other_current_asset' => 'other_current_asset',
            'cash' => 'cash',
            'bank' => 'bank',
            'fixed_asset' => 'fixed_asset',
            'other_current_liability' => 'other_current_liability',
            'credit_card' => 'credit_card',
            'long_term_liability' => 'long_term_liability',
            'other_liability' => 'other_liability',
            'equity' => 'equity',
            'income' => 'income',
            'other_income' => 'other_income',
            'expense' => 'expense',
            'cost_of_goods_sold' => 'cost_of_goods_sold',
            'other_expense' => 'other_expense',
        ];

        $normalizedType = strtolower(str_replace(' ', '_', $zohoType));
        return $typeMap[$normalizedType] ?? 'expense';
    }
}
