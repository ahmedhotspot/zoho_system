<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use App\Models\FinancingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinancingTypeController extends Controller
{
    /**
     * Display a listing of financing types
     */
    public function index(Request $request)
    {
        try {
            $query = FinancingType::orderBy('created_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name->ar', 'like', "%{$search}%")
                        ->orWhere('name->en', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if ($request->filled('status')) {
                $isActive = $request->status === 'active' ? 1 : 0;
                $query->where('is_active', $isActive);
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $financingTypes = $query->paginate($perPage);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $financingTypes,
                ]);
            }

            return view('dashboard.financing-type.index', compact('financingTypes'));

        } catch (\Exception $e) {
            Log::error('Error fetching financing types: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching financing types',
                ], 500);
            }

            return back()->with('error', 'Error fetching financing types: '.$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new financing type
     */
    public function create()
    {
        return view('dashboard.financing-type.create');
    }

    /**
     * Store a newly created financing type
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name.ar' => 'required|string|max:255',
                'name.en' => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $financingType = FinancingType::create([
                'name' => [
                    'ar' => $validated['name']['ar'],
                    'en' => $validated['name']['en'],
                ],
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('financing-types.index')
                ->with('success', __('dashboard.financing_type_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating financing type: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Error creating financing type: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified financing type
     */
    public function edit($id)
    {
        try {
            $financingType = FinancingType::findOrFail($id);

            return view('dashboard.financing-type.create', compact('financingType'));

        } catch (\Exception $e) {
            Log::error('Error loading financing type: '.$e->getMessage());

            return redirect()->route('financing-types.index')
                ->with('error', 'Error loading financing type');
        }
    }

    /**
     * Update the specified financing type
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name.ar' => 'required|string|max:255',
                'name.en' => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $financingType = FinancingType::findOrFail($id);

            $financingType->update([
                'name' => [
                    'ar' => $validated['name']['ar'],
                    'en' => $validated['name']['en'],
                ],
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('financing-types.index')
                ->with('success', __('dashboard.financing_type_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating financing type: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Error updating financing type: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified financing type
     */
    public function destroy($id)
    {
        try {
            $check = Companie::where('financing_type_id', $id)->first();
            if (! $check) {
                $financingType = FinancingType::findOrFail($id);
                $financingType->delete();

                return redirect()->route('financing-types.index')
                    ->with('success', __('dashboard.financing_type_deleted_successfully'));
            }

                return redirect()->route('financing-types.index')
                    ->with('success', __('dashboard.financing_type_deleted_not_successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting financing type: '.$e->getMessage());

            return back()->with('error', 'Error deleting financing type: '.$e->getMessage());
        }
    }
}
