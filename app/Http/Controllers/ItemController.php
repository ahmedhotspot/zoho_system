<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZohoBooksService;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Jobs\SyncItemsFromZoho;

class ItemController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    /**
     * Display a listing of items
     */
    public function index(Request $request)
    {
        try {
            // Get items from local database
            $query = Item::orderBy('name', 'asc');

            // Apply filters
            if ($request->filled('status')) {
                Log::info('Filtering by status: ' . $request->status);
                $query->where('status', $request->status);
            }

            if ($request->filled('item_type')) {
                Log::info('Filtering by item_type: ' . $request->item_type);
                $query->where('item_type', $request->item_type);
            }

            if ($request->filled('product_type')) {
                Log::info('Filtering by product_type: ' . $request->product_type);
                $query->where('product_type', $request->product_type);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                Log::info('Searching for: ' . $search);
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $items = $query->paginate($perPage);

            Log::info('Total items found: ' . $items->total());

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $items
                ]);
            }

            return view('dashboard.item.index', compact('items'));

        } catch (\Exception $e) {
            Log::error('Error fetching items: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching items'
                ], 500);
            }

            return back()->with('error', 'Error fetching items: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new item
     */
    public function create()
    {
        return view('dashboard.item.create');
    }

    /**
     * Store a newly created item
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'item_type' => 'required|in:sales,purchases,sales_and_purchases,inventory',
                'product_type' => 'required|in:goods,service',
                'rate' => 'required|numeric|min:0',
                'purchase_rate' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'is_taxable' => 'boolean',
            ]);

            // Create item in Zoho Books first
            $zohoItem = $this->books->createItem($validated);

            // Then save to local database
            $item = Item::create([
                'zoho_item_id' => $zohoItem['item']['item_id'],
                'name' => $zohoItem['item']['name'],
                'sku' => $zohoItem['item']['sku'] ?? null,
                'description' => $zohoItem['item']['description'] ?? null,
                'item_type' => $zohoItem['item']['item_type'] ?? 'sales',
                'product_type' => $zohoItem['item']['product_type'] ?? 'goods',
                'rate' => $zohoItem['item']['rate'] ?? 0,
                'purchase_rate' => $zohoItem['item']['purchase_rate'] ?? null,
                'unit' => $zohoItem['item']['unit'] ?? null,
                'status' => $zohoItem['item']['status'] ?? 'active',
                'is_taxable' => $zohoItem['item']['is_taxable'] ?? true,
                'synced_to_zoho' => true,
            ]);

            return redirect()->route('items.index')
                ->with('success', __('dashboard.item_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating item: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_item') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified item
     */
    public function show(Item $item)
    {
        return view('dashboard.item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item
     */
    public function edit(Item $item)
    {
        return view('dashboard.item.edit', compact('item'));
    }

    /**
     * Update the specified item
     */
    public function update(Request $request, Item $item)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'item_type' => 'required|in:sales,purchases,sales_and_purchases,inventory',
                'product_type' => 'required|in:goods,service',
                'rate' => 'required|numeric|min:0',
                'purchase_rate' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'is_taxable' => 'boolean',
            ]);

            // Update in Zoho Books first
            if ($item->zoho_item_id) {
                $this->books->updateItem($item->zoho_item_id, $validated);
            }

            // Then update local database
            $item->update($validated);

            return redirect()->route('items.show', $item->id)
                ->with('success', __('dashboard.item_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating item: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_item') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified item
     */
    public function destroy(Item $item)
    {
        try {
            // Delete from Zoho Books first
            if ($item->zoho_item_id) {
                $this->books->deleteItem($item->zoho_item_id);
            }

            // Then delete from local database (soft delete)
            $item->delete();

            return redirect()->route('items.index')
                ->with('success', __('dashboard.item_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting item: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_item') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync items from Zoho Books
     */
    public function syncFromZoho(Request $request)
    {
        try {
            set_time_limit(0);

            Log::info('Manual items sync triggered');

            // Run sync job synchronously
            $job = new SyncItemsFromZoho();
            $job->handle($this->books);

            // Always return JSON
            return response()->json([
                'success' => true,
                'message' => __('dashboard.items_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing items: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_items') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
