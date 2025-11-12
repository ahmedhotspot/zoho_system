<?php

namespace App\Jobs;

use App\Models\Expense;
use App\Models\Customer;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncExpensesFromZoho implements ShouldQueue
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

        Log::info('Starting expense sync from Zoho Books');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->books->getExpenses([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoExpenses = $response['expenses'] ?? [];
                $pageContext = $response['page_context'] ?? [];

                Log::info("Processing page {$page} with " . count($zohoExpenses) . ' expenses');

                foreach ($zohoExpenses as $zohoExpense) {
                    $this->syncExpense($zohoExpense);
                }

                $hasMorePages = $pageContext['has_more_page'] ?? false;
                $page++;
            }

            Log::info('Expense sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing expenses from Zoho Books: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single expense from Zoho Books
     */
    protected function syncExpense(array $zohoExpense): void
    {
        try {
            DB::beginTransaction();

            // Get full expense details
            $expenseDetails = $this->books->getExpense($zohoExpense['expense_id']);
            $zohoExpense = $expenseDetails['expense'] ?? $zohoExpense;

            // Find customer by Zoho contact ID if exists
            $customer = null;
            if (!empty($zohoExpense['customer_id'])) {
                $customer = Customer::where('zoho_contact_id', $zohoExpense['customer_id'])->first();
            }

            // Prepare expense data
            $expenseData = [
                'zoho_expense_id' => $zohoExpense['expense_id'],
                'zoho_account_id' => $zohoExpense['account_id'] ?? null,
                'zoho_customer_id' => $zohoExpense['customer_id'] ?? null,
                'zoho_vendor_id' => $zohoExpense['vendor_id'] ?? null,
                'zoho_project_id' => $zohoExpense['project_id'] ?? null,
                'customer_id' => $customer?->id,
                'account_name' => $zohoExpense['account_name'] ?? null,
                'expense_date' => $zohoExpense['date'] ?? now(),
                'amount' => $zohoExpense['amount'] ?? 0,
                'reference_number' => $zohoExpense['reference_number'] ?? null,
                'description' => $zohoExpense['description'] ?? null,
                'tax_id' => $zohoExpense['tax_id'] ?? null,
                'tax_name' => $zohoExpense['tax_name'] ?? null,
                'tax_percentage' => $zohoExpense['tax_percentage'] ?? 0,
                'tax_amount' => $zohoExpense['tax_amount'] ?? 0,
                'is_inclusive_tax' => $zohoExpense['is_inclusive_tax'] ?? false,
                'currency_id' => $zohoExpense['currency_id'] ?? null,
                'currency_code' => $zohoExpense['currency_code'] ?? 'SAR',
                'exchange_rate' => $zohoExpense['exchange_rate'] ?? 1,
                'sub_total' => $zohoExpense['sub_total'] ?? 0,
                'total' => $zohoExpense['total'] ?? $zohoExpense['amount'] ?? 0,
                'is_billable' => $zohoExpense['is_billable'] ?? false,
                'is_personal' => $zohoExpense['is_personal'] ?? false,
                'customer_name' => $zohoExpense['customer_name'] ?? null,
                'status' => $zohoExpense['status'] ?? 'unbilled',
                'invoice_id' => $zohoExpense['invoice_id'] ?? null,
                'invoice_number' => $zohoExpense['invoice_number'] ?? null,
                'project_name' => $zohoExpense['project_name'] ?? null,
                'vendor_name' => $zohoExpense['vendor_name'] ?? null,
                'expense_receipt_name' => $zohoExpense['expense_receipt_name'] ?? null,
                'expense_receipt_type' => $zohoExpense['expense_receipt_type'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            // Update or create expense
            $expense = Expense::updateOrCreate(
                ['zoho_expense_id' => $zohoExpense['expense_id']],
                $expenseData
            );

            if ($expense->wasRecentlyCreated) {
                $this->syncedCount++;
                Log::info("Created expense: {$expense->zoho_expense_id}");
            } else {
                $this->updatedCount++;
                Log::info("Updated expense: {$expense->zoho_expense_id}");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing expense: ' . $e->getMessage(), [
                'expense_id' => $zohoExpense['expense_id'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
