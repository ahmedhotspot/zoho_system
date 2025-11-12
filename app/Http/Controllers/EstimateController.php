<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Customer;
use App\Models\Item;
use App\Jobs\SyncEstimatesFromZoho;
use App\Services\ZohoBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstimateController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    /**
     * Display a listing of the estimates.
     */
    public function index(Request $request)
    {
        $query = Estimate::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('estimate_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $estimates = $query->orderBy('estimate_date', 'desc')->paginate(20);

        return view('dashboard.estimate.index', compact('estimates'));
    }

    /**
     * Show the form for creating a new estimate.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('contact_name')->get();
        $items = Item::where('status', 'active')->orderBy('name')->get();

        return view('dashboard.estimate.create', compact('customers', 'items'));
    }

    /**
     * Store a newly created estimate in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'estimate_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:estimate_date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'terms' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.rate' => 'required|numeric|min:0',
                'items.*.description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $customer = Customer::findOrFail($validated['customer_id']);

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $lineItems = [];

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $rate = $itemData['rate'];
                $amount = $quantity * $rate;

                $itemTax = 0;
                if ($item->is_taxable && $item->tax_percentage > 0) {
                    $itemTax = ($amount * $item->tax_percentage) / 100;
                }

                $subtotal += $amount;
                $taxAmount += $itemTax;

                $lineItems[] = [
                    'item_id' => $item->zoho_item_id,
                    'name' => $item->name,
                    'description' => $itemData['description'] ?? $item->description,
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'tax_id' => $item->tax_id ?? null,
                ];
            }

            $total = $subtotal + $taxAmount;

            // Create estimate in Zoho Books
            $zohoData = [
                'customer_id' => $customer->zoho_contact_id,
                'estimate_date' => $validated['estimate_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'line_items' => $lineItems,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
            ];

            $zohoEstimate = $this->books->createEstimate($zohoData);

            // Save to local database
            $estimate = Estimate::create([
                'zoho_estimate_id' => $zohoEstimate['estimate']['estimate_id'],
                'zoho_customer_id' => $customer->zoho_contact_id,
                'estimate_number' => $zohoEstimate['estimate']['estimate_number'],
                'estimate_date' => $validated['estimate_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'customer_id' => $customer->id,
                'customer_name' => $customer->contact_name,
                'customer_email' => $customer->email,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'currency_code' => 'SAR',
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ]);

            // Save estimate items
            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $rate = $itemData['rate'];
                $amount = $quantity * $rate;

                $itemTax = 0;
                $taxPercentage = 0;
                if ($item->is_taxable && $item->tax_percentage > 0) {
                    $taxPercentage = $item->tax_percentage;
                    $itemTax = ($amount * $taxPercentage) / 100;
                }

                $estimate->items()->create([
                    'zoho_item_id' => $item->zoho_item_id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'description' => $itemData['description'] ?? $item->description,
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'amount' => $amount,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount' => $itemTax,
                ]);
            }

            DB::commit();

            return redirect()->route('estimates.show', $estimate->id)
                ->with('success', __('dashboard.estimate_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating estimate: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_estimate') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified estimate.
     */
    public function show(Estimate $estimate)
    {
        $estimate->load(['items', 'customer']);
        return view('dashboard.estimate.show', compact('estimate'));
    }

    /**
     * Show the form for editing the specified estimate.
     */
    public function edit(Estimate $estimate)
    {
        $estimate->load('items');
        $customers = Customer::where('status', 'active')->orderBy('contact_name')->get();
        $items = Item::where('status', 'active')->orderBy('name')->get();

        return view('dashboard.estimate.edit', compact('estimate', 'customers', 'items'));
    }

    /**
     * Update the specified estimate in storage.
     */
    public function update(Request $request, Estimate $estimate)
    {
        try {
            $validated = $request->validate([
                'estimate_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:estimate_date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'terms' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.rate' => 'required|numeric|min:0',
                'items.*.description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $lineItems = [];

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $rate = $itemData['rate'];
                $amount = $quantity * $rate;

                $itemTax = 0;
                if ($item->is_taxable && $item->tax_percentage > 0) {
                    $itemTax = ($amount * $item->tax_percentage) / 100;
                }

                $subtotal += $amount;
                $taxAmount += $itemTax;

                $lineItems[] = [
                    'item_id' => $item->zoho_item_id,
                    'name' => $item->name,
                    'description' => $itemData['description'] ?? $item->description,
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'tax_id' => $item->tax_id ?? null,
                ];
            }

            $total = $subtotal + $taxAmount;

            // Update estimate in Zoho Books
            if ($estimate->zoho_estimate_id) {
                $zohoData = [
                    'estimate_date' => $validated['estimate_date'],
                    'expiry_date' => $validated['expiry_date'] ?? null,
                    'reference_number' => $validated['reference_number'] ?? null,
                    'line_items' => $lineItems,
                    'notes' => $validated['notes'] ?? null,
                    'terms' => $validated['terms'] ?? null,
                ];

                $this->books->updateEstimate($estimate->zoho_estimate_id, $zohoData);
            }

            // Update local database
            $estimate->update([
                'estimate_date' => $validated['estimate_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'last_synced_at' => now(),
            ]);

            // Delete old items and create new ones
            $estimate->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $item = Item::find($itemData['item_id']);
                $quantity = $itemData['quantity'];
                $rate = $itemData['rate'];
                $amount = $quantity * $rate;

                $itemTax = 0;
                $taxPercentage = 0;
                if ($item->is_taxable && $item->tax_percentage > 0) {
                    $taxPercentage = $item->tax_percentage;
                    $itemTax = ($amount * $taxPercentage) / 100;
                }

                $estimate->items()->create([
                    'zoho_item_id' => $item->zoho_item_id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'description' => $itemData['description'] ?? $item->description,
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'amount' => $amount,
                    'tax_percentage' => $taxPercentage,
                    'tax_amount' => $itemTax,
                ]);
            }

            DB::commit();

            return redirect()->route('estimates.show', $estimate->id)
                ->with('success', __('dashboard.estimate_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating estimate: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_estimate') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified estimate from storage.
     */
    public function destroy(Estimate $estimate)
    {
        try {
            DB::beginTransaction();

            // Delete from Zoho Books if it exists there
            if ($estimate->zoho_estimate_id) {
                try {
                    $this->books->deleteEstimate($estimate->zoho_estimate_id);
                } catch (\Exception $e) {
                    Log::warning('Could not delete estimate from Zoho Books: ' . $e->getMessage());
                    // Continue with local deletion even if Zoho deletion fails
                }
            }

            // Soft delete from local database
            $estimate->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.estimate_deleted_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting estimate: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_deleting_estimate') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync estimates from Zoho Books.
     */
    public function syncFromZoho(Request $request)
    {
        try {
            set_time_limit(0);

            SyncEstimatesFromZoho::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.estimates_synced_successfully'),
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing estimates: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_estimates') . ': ' . $e->getMessage(),
            ], 500);
        }
    }
}
