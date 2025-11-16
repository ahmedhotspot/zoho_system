<?php

namespace App\Http\Controllers;

use App\Models\Financing;
use App\Models\Company;
use App\Models\FinancingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        return view('dashboard.financings.show', compact('financing', 'company'));
    }


}
