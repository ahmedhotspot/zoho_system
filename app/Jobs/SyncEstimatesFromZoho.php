<?php

namespace App\Jobs;

use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\Customer;
use App\Models\Item;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SyncEstimatesFromZoho implements ShouldQueue
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

        Log::info('Starting estimate sync from Zoho Books');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->books->getEstimates([
                    'sort_column' => 'last_modified_time',
                    'sort_order' => 'D',
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoEstimates = $response['estimates'] ?? [];
                $pageContext = $response['page_context'] ?? [];

                Log::info("Processing page {$page} with " . count($zohoEstimates) . ' estimates');

                foreach ($zohoEstimates as $zohoEstimate) {
                    $this->syncEstimate($zohoEstimate);
                }

                $hasMorePages = $pageContext['has_more_page'] ?? false;
                $page++;
            }

            Log::info('Estimate sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing estimates from Zoho Books: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single estimate from Zoho to local database
     */
    protected function syncEstimate(array $zohoEstimate): void
    {
        DB::beginTransaction();

        try {
            // Check if estimate already exists locally
            $localEstimate = Estimate::where('zoho_estimate_id', $zohoEstimate['estimate_id'])->first();

            // Get full estimate details from Zoho
            $fullEstimateResponse = $this->books->getEstimate($zohoEstimate['estimate_id']);
            $fullEstimate = $fullEstimateResponse['estimate'] ?? $zohoEstimate;

            // Find customer by zoho_contact_id
            $customer = null;
            if (!empty($fullEstimate['customer_id'])) {
                $customer = Customer::where('zoho_contact_id', $fullEstimate['customer_id'])->first();
            }

            // Prepare estimate data
            $estimateData = [
                'zoho_estimate_id' => $fullEstimate['estimate_id'],
                'zoho_customer_id' => $fullEstimate['customer_id'] ?? null,
                'estimate_number' => $fullEstimate['estimate_number'],
                'estimate_date' => Carbon::parse($fullEstimate['date']),
                'expiry_date' => isset($fullEstimate['expiry_date']) ? Carbon::parse($fullEstimate['expiry_date']) : null,
                'reference_number' => $fullEstimate['reference_number'] ?? null,
                'customer_id' => $customer?->id,
                'customer_name' => $fullEstimate['customer_name'] ?? '',
                'customer_email' => $fullEstimate['email'] ?? null,
                'customer_address' => $this->formatAddress($fullEstimate),
                'subtotal' => $fullEstimate['sub_total'] ?? 0,
                'tax_amount' => $fullEstimate['tax_total'] ?? 0,
                'discount_amount' => $fullEstimate['discount_total'] ?? 0,
                'adjustment' => $fullEstimate['adjustment'] ?? 0,
                'total' => $fullEstimate['total'] ?? 0,
                'currency_code' => $fullEstimate['currency_code'] ?? 'SAR',
                'status' => $this->mapZohoStatus($fullEstimate['status'] ?? 'draft'),
                'notes' => $fullEstimate['notes'] ?? null,
                'terms' => $fullEstimate['terms'] ?? null,
                'salesperson_name' => $fullEstimate['salesperson_name'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            if ($localEstimate) {
                // Update existing estimate
                $localEstimate->update($estimateData);
                $this->updatedCount++;
            } else {
                // Create new estimate
                $localEstimate = Estimate::create($estimateData);
                $this->syncedCount++;
            }

            // Sync estimate items
            $this->syncEstimateItems($localEstimate, $fullEstimate['line_items'] ?? []);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing estimate ' . ($zohoEstimate['estimate_number'] ?? 'unknown') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync estimate items
     */
    protected function syncEstimateItems(Estimate $estimate, array $zohoItems): void
    {
        // Delete existing items
        $estimate->items()->delete();

        // Create new items
        foreach ($zohoItems as $zohoItem) {
            // Find item by zoho_item_id
            $item = null;
            if (!empty($zohoItem['item_id'])) {
                $item = Item::where('zoho_item_id', $zohoItem['item_id'])->first();
            }

            EstimateItem::create([
                'estimate_id' => $estimate->id,
                'zoho_item_id' => $zohoItem['item_id'] ?? null,
                'zoho_line_item_id' => $zohoItem['line_item_id'] ?? null,
                'item_id' => $item?->id,
                'item_name' => $zohoItem['name'] ?? '',
                'description' => $zohoItem['description'] ?? null,
                'quantity' => $zohoItem['quantity'] ?? 1,
                'rate' => $zohoItem['rate'] ?? 0,
                'amount' => $zohoItem['item_total'] ?? 0,
                'tax_percentage' => $zohoItem['tax_percentage'] ?? 0,
                'tax_amount' => $zohoItem['item_tax'] ?? 0,
                'discount_percentage' => $zohoItem['discount'] ?? 0,
                'discount_amount' => $zohoItem['discount_amount'] ?? 0,
            ]);
        }
    }

    /**
     * Format address from Zoho estimate data
     */
    protected function formatAddress(array $estimate): ?string
    {
        $addressParts = [];

        if (!empty($estimate['billing_address'])) {
            $billing = $estimate['billing_address'];
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
     * Map Zoho status to local status
     */
    protected function mapZohoStatus(string $zohoStatus): string
    {
        return match(strtolower($zohoStatus)) {
            'draft' => 'draft',
            'sent' => 'sent',
            'accepted' => 'accepted',
            'declined' => 'declined',
            'invoiced' => 'invoiced',
            'expired' => 'expired',
            default => 'draft',
        };
    }
}
