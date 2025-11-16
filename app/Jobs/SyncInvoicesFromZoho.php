<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SyncInvoicesFromZoho implements ShouldQueue
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

        Log::info('Starting invoice sync from Zoho Books');

        try {
            // Get all invoices from Zoho Books
            $response = $this->books->getInvoices([
                'sort_column' => 'last_modified_time',
                'sort_order' => 'D',
            ]);

            $zohoInvoices = $response['invoices'] ?? [];

            Log::info('Found ' . count($zohoInvoices) . ' invoices in Zoho Books');

            foreach ($zohoInvoices as $zohoInvoice) {
                $this->syncInvoice($zohoInvoice);
            }

            Log::info('Invoice sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing invoices from Zoho Books: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single invoice from Zoho to local database
     */
    protected function syncInvoice(array $zohoInvoice): void
    {
        DB::beginTransaction();

        try {
            // Check if invoice already exists locally
            $localInvoice = Invoice::where('zoho_invoice_id', $zohoInvoice['invoice_id'])->first();

            // Get full invoice details from Zoho
            $fullInvoiceResponse = $this->books->getInvoice($zohoInvoice['invoice_id']);
            $fullInvoice = $fullInvoiceResponse['invoice'] ?? $zohoInvoice;

            // Prepare invoice data
            $invoiceData = [
                'zoho_invoice_id' => $fullInvoice['invoice_id'],
                'zoho_customer_id' => $fullInvoice['customer_id'] ?? null,
                'invoice_url' => $fullInvoice['invoice_url'] ?? null,
                'invoice_number' => $fullInvoice['invoice_number'],
                'invoice_date' => Carbon::parse($fullInvoice['date']),
                'due_date' => isset($fullInvoice['due_date']) ? Carbon::parse($fullInvoice['due_date']) : null,
                'customer_name' => $fullInvoice['customer_name'] ?? '',
                'customer_email' => $fullInvoice['email'] ?? null,
                'customer_address' => $this->formatAddress($fullInvoice),
                'subtotal' => $fullInvoice['sub_total'] ?? 0,
                'tax_amount' => $fullInvoice['tax_total'] ?? 0,
                'discount_amount' => $fullInvoice['discount_total'] ?? 0,
                'total' => $fullInvoice['total'] ?? 0,
                'balance' => $fullInvoice['balance'] ?? 0,
                'currency_code' => $fullInvoice['currency_code'] ?? 'SAR',
                'status' => $this->mapZohoStatus($fullInvoice['status'] ?? 'draft'),
                'notes' => $fullInvoice['notes'] ?? null,
                'terms' => $fullInvoice['terms'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localInvoice) {
                // Update existing invoice
                $localInvoice->update($invoiceData);

                // Delete old items and create new ones
                $localInvoice->items()->delete();

                $this->updatedCount++;
                Log::info('Updated invoice: ' . $fullInvoice['invoice_number']);
            } else {
                // Create new invoice
                $localInvoice = Invoice::create($invoiceData);

                $this->syncedCount++;
                Log::info('Created new invoice: ' . $fullInvoice['invoice_number']);
            }

            // Sync invoice items
            if (isset($fullInvoice['line_items']) && is_array($fullInvoice['line_items'])) {
                foreach ($fullInvoice['line_items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $localInvoice->id,
                        'zoho_item_id' => $item['item_id'] ?? null,
                        'zoho_line_item_id' => $item['line_item_id'] ?? null,
                        'item_name' => $item['name'] ?? $item['item_name'] ?? '',
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['quantity'] ?? 1,
                        'rate' => $item['rate'] ?? 0,
                        'amount' => $item['item_total'] ?? ($item['quantity'] * $item['rate']),
                        'tax_percentage' => $item['tax_percentage'] ?? 0,
                        'tax_amount' => $item['item_tax'] ?? 0,
                        'discount_amount' => $item['discount_amount'] ?? 0,
                        'unit' => $item['unit'] ?? null,
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing invoice ' . ($zohoInvoice['invoice_number'] ?? 'unknown') . ': ' . $e->getMessage());
        }
    }

    /**
     * Format customer address from Zoho invoice
     */
    protected function formatAddress(array $invoice): ?string
    {
        $addressParts = [];

        if (!empty($invoice['billing_address'])) {
            $billing = $invoice['billing_address'];

            if (!empty($billing['address'])) $addressParts[] = $billing['address'];
            if (!empty($billing['street2'])) $addressParts[] = $billing['street2'];
            if (!empty($billing['city'])) $addressParts[] = $billing['city'];
            if (!empty($billing['state'])) $addressParts[] = $billing['state'];
            if (!empty($billing['zip'])) $addressParts[] = $billing['zip'];
            if (!empty($billing['country'])) $addressParts[] = $billing['country'];
        }

        return !empty($addressParts) ? implode(', ', $addressParts) : null;
    }

    /**
     * Map Zoho invoice status to local status
     */
    protected function mapZohoStatus(string $zohoStatus): string
    {
        return match (strtolower($zohoStatus)) {
            'draft' => 'draft',
            'sent' => 'sent',
            'paid' => 'paid',
            'overdue' => 'overdue',
            'void' => 'void',
            'partially_paid' => 'partially_paid',
            default => 'draft',
        };
    }
}
