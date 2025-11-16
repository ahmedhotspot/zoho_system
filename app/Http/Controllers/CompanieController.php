<?php

namespace App\Http\Controllers;

use App\Models\Companie;
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
            $query = Companie::orderBy('created_at', 'desc');

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
                    'data' => $companies
                ]);
            }

            return view('dashboard.companie.index', compact('companies'));

        } catch (\Exception $e) {
            Log::error('Error fetching companies: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching companies'
                ], 500);
            }

            return back()->with('error', 'Error fetching companies: ' . $e->getMessage());
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
                    'contract_value' => 'nullable|integer|min:0|max:100',
                    'is_active' => 'boolean',
                ]);

                $company = Companie::create([
                    'name' => $validated['name'],
                    'contract_type' => $validated['contract_type'],
                    'contract_value' => $validated['contract_type'] === 'percentage' ? $validated['contract_value'] : null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]);

                return redirect()->route('companies.index')
                    ->with('success', __('dashboard.company_created_successfully'));

            } catch (\Exception $e) {
                Log::error('Error creating company: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'Error creating company: ' . $e->getMessage());
            }
        }




    /**
     * Show the form for editing the specified company
     */
    public function edit($id)
    {
        try {
            $company = Companie::findOrFail($id);
            return view('dashboard.companie.create', compact('company'));

        } catch (\Exception $e) {
            Log::error('Error loading company: ' . $e->getMessage());
            return redirect()->route('companies.index')
                ->with('error', 'Error loading company');
        }
    }


       /**
         * Update the specified company
         */
        public function update(Request $request, $id)
        {

            try {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'contract_type' => 'required|in:percentage,fixed',
                    'contract_value' => 'nullable|integer|min:0|max:100',
                    'is_active' => 'boolean',
                ]);

                $company = Companie::findOrFail($id);
                $company->update([
                    'name' => $validated['name'],
                    'contract_type' => $validated['contract_type'],
                    'contract_value' => $validated['contract_type'] === 'percentage' ? $validated['contract_value'] : null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]);

                return redirect()->route('companies.index')
                    ->with('success', __('dashboard.company_updated_successfully'));

            } catch (\Exception $e) {
                Log::error('Error updating company: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'Error updating company: ' . $e->getMessage());
            }
        }



    /**
     * Remove the specified company
     */
    public function destroy($id)
    {
        try {
            $company = Companie::findOrFail($id);
            $company->delete();

            return redirect()->route('companies.index')
                ->with('success', __('dashboard.company_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting company: ' . $e->getMessage());
            return back()->with('error', 'Error deleting company: ' . $e->getMessage());
        }
    }
}

