<?php

namespace App\Jobs;

use App\Models\Item;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncItemsFromZoho implements ShouldQueue
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

        Log::info('Starting items sync from Zoho Books');

        try {
            // Get all items from Zoho Books
            $page = 1;
            $perPage = 200;
            $hasMore = true;

            while ($hasMore) {
                $response = $this->books->getItems([
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_column' => 'last_modified_time',
                    'sort_order' => 'D',
                ]);

                $zohoItems = $response['items'] ?? [];

                if (empty($zohoItems)) {
                    $hasMore = false;
                    break;
                }

                Log::info("Processing page {$page} with " . count($zohoItems) . ' items');

                foreach ($zohoItems as $zohoItem) {
                    try {
                        $this->syncItem($zohoItem);
                    } catch (\Exception $e) {
                        $this->errorCount++;
                        Log::error('Error syncing item: ' . $e->getMessage(), [
                            'item_id' => $zohoItem['item_id'] ?? 'unknown',
                            'item_name' => $zohoItem['name'] ?? 'unknown',
                        ]);
                    }
                }

                // Check if there are more pages
                $pageContext = $response['page_context'] ?? [];
                $hasMore = ($pageContext['has_more_page'] ?? false);
                $page++;
            }

            Log::info('Items sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting items sync: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single item
     */
    protected function syncItem(array $zohoItem): void
    {
        DB::beginTransaction();

        try {
            $itemData = [
                'zoho_item_id' => $zohoItem['item_id'],
                'name' => $zohoItem['name'],
                'sku' => $zohoItem['sku'] ?? null,
                'description' => $zohoItem['description'] ?? null,
                'item_type' => $this->mapItemType($zohoItem['item_type'] ?? 'sales'),
                'product_type' => $zohoItem['product_type'] ?? 'goods',
                'rate' => $zohoItem['rate'] ?? 0,
                'purchase_rate' => $zohoItem['purchase_rate'] ?? null,
                'tax_id' => $zohoItem['tax_id'] ?? null,
                'tax_name' => $zohoItem['tax_name'] ?? null,
                'tax_percentage' => $zohoItem['tax_percentage'] ?? 0,
                'tax_type' => $zohoItem['tax_type'] ?? null,
                'account_id' => $zohoItem['account_id'] ?? null,
                'account_name' => $zohoItem['account_name'] ?? null,
                'purchase_account_id' => $zohoItem['purchase_account_id'] ?? null,
                'purchase_account_name' => $zohoItem['purchase_account_name'] ?? null,
                'inventory_account_id' => $zohoItem['inventory_account_id'] ?? null,
                'inventory_account_name' => $zohoItem['inventory_account_name'] ?? null,
                'initial_stock' => $zohoItem['initial_stock'] ?? null,
                'stock_on_hand' => $zohoItem['stock_on_hand'] ?? null,
                'reorder_level' => $zohoItem['reorder_level'] ?? null,
                'unit' => $zohoItem['unit'] ?? null,
                'status' => $zohoItem['status'] ?? 'active',
                'is_taxable' => $zohoItem['is_taxable'] ?? true,
                'is_returnable' => $zohoItem['is_returnable'] ?? false,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            // Find existing item by Zoho ID
            $item = Item::where('zoho_item_id', $zohoItem['item_id'])->first();

            if ($item) {
                // Update existing item
                $item->update($itemData);
                $this->updatedCount++;
                Log::info('Updated item: ' . $item->name);
            } else {
                // Create new item
                $item = Item::create($itemData);
                $this->syncedCount++;
                Log::info('Created item: ' . $item->name);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Map Zoho item type to our enum
     */
    protected function mapItemType(string $type): string
    {
        return match($type) {
            'sales' => 'sales',
            'purchases' => 'purchases',
            'sales_and_purchases' => 'sales_and_purchases',
            'inventory' => 'inventory',
            default => 'sales',
        };
    }
}
