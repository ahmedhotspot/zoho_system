<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\BillItem;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SyncBillsFromZoho implements ShouldQueue
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

        Log::info('Starting bill sync from Zoho Books');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->books->getBills([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoBills = $response['bills'] ?? [];
                $pageContext = $response['page_context'] ?? [];

                Log::info("Processing page {$page} with " . count($zohoBills) . " bills");

                foreach ($zohoBills as $zohoBill) {
                    $this->syncBill($zohoBill);
                }

                $hasMorePages = $pageContext['has_more_page'] ?? false;
                $page++;
            }

            Log::info('Bill sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing bills from Zoho Books: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single bill from Zoho to local database
     */
    protected function syncBill(array $zohoBill): void
    {
        DB::beginTransaction();

        try {
            // Check if bill already exists locally
            $localBill = Bill::where('zoho_bill_id', $zohoBill['bill_id'])->first();

            // Get full bill details from Zoho
            $fullBillResponse = $this->books->getBill($zohoBill['bill_id']);
            $fullBill = $fullBillResponse['bill'] ?? $zohoBill;

            // Prepare bill data
            $billData = [
                'zoho_bill_id' => $fullBill['bill_id'],
                'zoho_vendor_id' => $fullBill['vendor_id'] ?? null,
                'bill_number' => $fullBill['bill_number'],
                'bill_date' => Carbon::parse($fullBill['date']),
                'due_date' => isset($fullBill['due_date']) ? Carbon::parse($fullBill['due_date']) : null,
                'reference_number' => $fullBill['reference_number'] ?? null,
                'vendor_name' => $fullBill['vendor_name'] ?? '',
                'vendor_email' => $fullBill['email'] ?? null,
                'subtotal' => $fullBill['sub_total'] ?? 0,
                'tax_amount' => $fullBill['tax_total'] ?? 0,
                'discount_amount' => $fullBill['discount_total'] ?? 0,
                'total' => $fullBill['total'] ?? 0,
                'balance' => $fullBill['balance'] ?? 0,
                'currency_code' => $fullBill['currency_code'] ?? 'SAR',
                'status' => $this->mapZohoStatus($fullBill['status'] ?? 'draft'),
                'notes' => $fullBill['notes'] ?? null,
                'terms' => $fullBill['terms'] ?? null,
                'payment_made' => $fullBill['payment_made'] ?? 0,
                'is_item_level_tax_calc' => $fullBill['is_item_level_tax_calc'] ?? false,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localBill) {
                // Update existing bill
                $localBill->update($billData);

                // Delete old items and create new ones
                $localBill->items()->delete();

                $this->updatedCount++;
                Log::info('Updated bill: ' . $fullBill['bill_number']);
            } else {
                // Create new bill
                $localBill = Bill::create($billData);

                $this->syncedCount++;
                Log::info('Created new bill: ' . $fullBill['bill_number']);
            }

            // Sync bill items
            if (isset($fullBill['line_items']) && is_array($fullBill['line_items'])) {
                foreach ($fullBill['line_items'] as $item) {
                    BillItem::create([
                        'bill_id' => $localBill->id,
                        'zoho_item_id' => $item['item_id'] ?? null,
                        'zoho_line_item_id' => $item['line_item_id'] ?? null,
                        'item_name' => $item['name'] ?? $item['item_name'] ?? '',
                        'description' => $item['description'] ?? null,
                        'account_name' => $item['account_name'] ?? null,
                        'quantity' => $item['quantity'] ?? 1,
                        'rate' => $item['rate'] ?? 0,
                        'amount' => $item['item_total'] ?? ($item['quantity'] * $item['rate']),
                        'tax_id' => $item['tax_id'] ?? null,
                        'tax_name' => $item['tax_name'] ?? null,
                        'tax_percentage' => $item['tax_percentage'] ?? 0,
                        'tax_amount' => $item['item_tax'] ?? 0,
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing bill ' . ($zohoBill['bill_number'] ?? 'unknown') . ': ' . $e->getMessage());
        }
    }

    /**
     * Map Zoho bill status to local status
     */
    protected function mapZohoStatus(string $zohoStatus): string
    {
        return match (strtolower($zohoStatus)) {
            'draft' => 'draft',
            'open' => 'open',
            'paid' => 'paid',
            'overdue' => 'overdue',
            'void' => 'void',
            'partially_paid' => 'partially_paid',
            default => 'draft',
        };
    }
}
