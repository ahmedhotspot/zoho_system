<?php

namespace App\Http\Controllers;

use App\Models\Financing;
use App\Models\Company;
use App\Models\FinancingType;
use App\Models\FinancingPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Financing::with(['financingType']);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('iqama_number', 'like', "%{$search}%")
                  ->orWhere('application_id', 'like', "%{$search}%");
            });
        }

        // Filter by financing type
        if ($request->has('financing_type_id') && $request->financing_type_id) {
            $query->where('financing_type_id', $request->financing_type_id);
        }

        // Filter by company
        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $financings = $query->paginate(15);

        // Get all financing types and companies for filters
        $financingTypes = FinancingType::where('is_active', true)->get();
        $companies = Company::where('is_active', true)->get();

        return view('dashboard.financings.index', compact('financings', 'financingTypes', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $financingTypes = FinancingType::where('is_active', true)->get();
        $companies = Company::where('is_active', true)->get();

        return view('dashboard.financings.create', compact('financingTypes', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'iqama_number' => 'nullable|string|max:255',
            'application_id' => 'nullable|string|max:255',
            'financingcompanies' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'company_id' => 'required',
            'financing_type_id' => 'required|exists:financing_types,id',
        ]);

        Financing::create($validated);

        return redirect()->route('financings.index')
            ->with('success', __('financing.financing_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
 public function show($id)
    {
        $financing = Financing::with(['financingType'])->findOrFail($id);

        $company = Company::where('user_id', $financing->company_id)
            ->where('financing_type_id', $financing->financing_type_id)
            ->first();

        // Get price history ordered by newest first
        $priceHistory = FinancingPriceHistory::where('financing_id', $id)
            ->with(['user', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.financings.show', compact('financing', 'company', 'priceHistory'));
    }

    /**
     * Update the price of a financing and create price history record.
     */
    public function updatePrice(Request $request, $id)
    {
        $validated = $request->validate([
            'new_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ], [
            'new_price.required' => __('financing.new_price_required'),
            'new_price.numeric' => __('financing.new_price_must_be_number'),
            'new_price.min' => __('financing.new_price_must_be_positive'),
        ]);

        try {
            DB::beginTransaction();

            $financing = Financing::findOrFail($id);
            $oldPrice = $financing->price;
            $newPrice = $validated['new_price'];

            // Check if price actually changed
            if ($oldPrice == $newPrice) {
                return response()->json([
                    'success' => false,
                    'message' => __('financing.price_not_changed')
                ], 400);
            }

            // Create price history record
            FinancingPriceHistory::create([
                'financing_id' => $financing->id,
                'company_id' => $financing->company_id,
                'user_id' => Auth::id(),
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update financing price
            $financing->update([
                'price' => $newPrice
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('financing.price_updated_successfully'),
                'data' => [
                    'old_price' => number_format($oldPrice, 2),
                    'new_price' => number_format($newPrice, 2),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating financing price: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('financing.error_updating_price')
            ], 500);
        }
    }

}
