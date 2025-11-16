<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use App\Models\Company;
use App\Models\FinancingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanieController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index(Request $request)
    {
        try {
            $query = Company::orderBy('created_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            // Apply status filter
            if ($request->filled('status')) {
                $isActive = $request->status === 'active' ? 1 : 0;
                $query->where('is_active', $isActive);
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $companies = $query->paginate($perPage);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $companies,
                ]);
            }

            return view('dashboard.companie.index', compact('companies'));

        } catch (\Exception $e) {
            Log::error('Error fetching companies: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching companies',
                ], 500);
            }

            return back()->with('error', 'Error fetching companies: '.$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new company
     */
    public function create()
    {

        return view('dashboard.companie.create');
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contract_type' => 'required|in:percentage,fixed',
                'contract_value_percentage' => 'nullable|integer|min:0|max:100',
                'contract_value_fixed' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);

            if ($validated['contract_type'] === 'percentage' && is_null($validated['contract_value_percentage'])) {
                return back()
                    ->withErrors(['contract_value_percentage' => 'النسبة مطلوبة عند اختيار نوع نسبة'])
                    ->withInput();
            }

            if ($validated['contract_type'] === 'fixed' && is_null($validated['contract_value_fixed'])) {
                return back()
                    ->withErrors(['contract_value_fixed' => 'القيمة مطلوبة عند اختيار نوع ثابت'])
                    ->withInput();
            }

            $contractValue = $validated['contract_type'] === 'percentage'
                ? $validated['contract_value_percentage']
                : $validated['contract_value_fixed'];

            Company::create([
                'name' => $validated['name'],
                'contract_type' => $validated['contract_type'],
                'contract_value' => $contractValue,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('companies.index')
                ->with('success', __('dashboard.company_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating company: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Error creating company: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified company
     */
    public function edit($id)
    {
        try {
            $company = Company::findOrFail($id);
            $financing_types = FinancingType::where('is_active', true)->get();

            return view('dashboard.companie.create', compact('company', 'financing_types'));

        } catch (\Exception $e) {
            Log::error('Error loading company: '.$e->getMessage());

            return redirect()->route('companies.index')
                ->with('error', 'Error loading company');
        }
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_type' => 'required|in:percentage,fixed',
            'contract_value_percentage' => 'nullable|integer|min:0|max:100',
            'contract_value_fixed' => 'nullable|integer|min:0',
            'financing_type_id' => 'required|exists:financing_types,id',
            'is_active' => 'boolean',
        ]);


        $contractValue = $validated['contract_type'] === 'percentage'
            ? $validated['contract_value_percentage']
            : $validated['contract_value_fixed'];

        $company = Company::findOrFail($id);

        $company->update([
            'name' => $validated['name'],
            'financing_type_id' => $validated['financing_type_id'],
            'contract_type' => $validated['contract_type'],
            'contract_value' => $contractValue,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('companies.index')
            ->with('success', __('dashboard.company_updated_successfully'));
    }

    /**
     * Remove the specified company
     */
    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return redirect()->route('companies.index')
                ->with('success', __('dashboard.company_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting company: '.$e->getMessage());

            return back()->with('error', 'Error deleting company: '.$e->getMessage());
        }
    }
}
