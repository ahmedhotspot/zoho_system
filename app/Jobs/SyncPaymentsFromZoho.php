<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncPaymentsFromZoho implements ShouldQueue
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

        Log::info('Starting payments sync from Zoho Books');

        try {
            // Get all payments from Zoho Books
            $page = 1;
            $perPage = 200;
            $hasMore = true;

            while ($hasMore) {
                $response = $this->books->getPayments([
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_column' => 'last_modified_time',
                    'sort_order' => 'D',
                ]);

                $zohoPayments = $response['customerpayments'] ?? [];

                if (empty($zohoPayments)) {
                    $hasMore = false;
                    break;
                }

                Log::info("Processing page {$page} with " . count($zohoPayments) . ' payments');

                foreach ($zohoPayments as $zohoPayment) {
                    try {
                        $this->syncPayment($zohoPayment);
                    } catch (\Exception $e) {
                        $this->errorCount++;
                        Log::error('Error syncing payment: ' . $e->getMessage(), [
                            'payment_id' => $zohoPayment['payment_id'] ?? 'unknown',
                            'payment_number' => $zohoPayment['payment_number'] ?? 'unknown',
                        ]);
                    }
                }

                // Check if there are more pages
                $pageContext = $response['page_context'] ?? [];
                $hasMore = ($pageContext['has_more_page'] ?? false);
                $page++;
            }

            Log::info('Payments sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting payments sync: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single payment
     */
    protected function syncPayment(array $zohoPayment): void
    {
        DB::beginTransaction();

        try {
            // Find customer by zoho_contact_id
            $customer = Customer::where('zoho_contact_id', $zohoPayment['customer_id'] ?? null)->first();

            // Find invoice by zoho_invoice_id (if payment is for single invoice)
            $invoice = null;
            $invoices = $zohoPayment['invoices'] ?? [];
            if (count($invoices) === 1) {
                $invoice = Invoice::where('zoho_invoice_id', $invoices[0]['invoice_id'] ?? null)->first();
            }

            $paymentData = [
                'zoho_payment_id' => $zohoPayment['payment_id'],
                'zoho_customer_id' => $zohoPayment['customer_id'] ?? null,
                'zoho_invoice_id' => count($invoices) === 1 ? ($invoices[0]['invoice_id'] ?? null) : null,
                'payment_number' => $zohoPayment['payment_number'] ?? null,
                'payment_date' => $zohoPayment['date'] ?? now()->format('Y-m-d'),
                'amount' => $zohoPayment['amount'] ?? 0,
                'payment_mode' => $zohoPayment['payment_mode'] ?? null,
                'reference_number' => $zohoPayment['reference_number'] ?? null,
                'customer_name' => $zohoPayment['customer_name'] ?? null,
                'customer_id' => $customer?->id,
                'invoice_id' => $invoice?->id,
                'amount_applied' => $zohoPayment['amount_applied'] ?? $zohoPayment['amount'] ?? 0,
                'currency_code' => $zohoPayment['currency_code'] ?? 'SAR',
                'description' => $zohoPayment['description'] ?? null,
                'bank_charges' => $zohoPayment['bank_charges'] ?? null,
                'tax_account_id' => $zohoPayment['tax_account_id'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            // Find existing payment by Zoho ID
            $payment = Payment::where('zoho_payment_id', $zohoPayment['payment_id'])->first();

            if ($payment) {
                // Update existing payment
                $payment->update($paymentData);
                $this->updatedCount++;
                Log::info('Updated payment: ' . $payment->payment_number);
            } else {
                // Create new payment
                $payment = Payment::create($paymentData);
                $this->syncedCount++;
                Log::info('Created payment: ' . $payment->payment_number);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
