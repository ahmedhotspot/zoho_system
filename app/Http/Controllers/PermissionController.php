<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        try {
            $query = Permission::orderBy('created_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $permissions = $query->paginate($perPage);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $permissions,
                ]);
            }

            return view('dashboard.permission.index', compact('permissions'));

        } catch (\Exception $e) {
            Log::error('Error fetching permissions: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.error_occurred'),
                ], 500);
            }

            return redirect()->back()->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        return view('dashboard.permission.create');
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.permission_name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('dashboard.permission_name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.permission_name'), 'max' => 255]),
        ]);

        try {
            $permission = Permission::create(['name' => $validated['name']]);

            Log::info('Permission created successfully', ['permission_id' => $permission->id, 'name' => $permission->name]);

            return redirect()->route('permissions.index')
                ->with('success', __('dashboard.permission_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating permission: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return view('dashboard.permission.create', compact('permission'));

        } catch (\Exception $e) {
            Log::error('Error fetching permission: '.$e->getMessage());
            return redirect()->route('permissions.index')
                ->with('error', __('dashboard.permission_not_found'));
        }
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.permission_name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('dashboard.permission_name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.permission_name'), 'max' => 255]),
        ]);

        try {
            $permission->update(['name' => $validated['name']]);

            Log::info('Permission updated successfully', ['permission_id' => $permission->id]);

            return redirect()->route('permissions.index')
                ->with('success', __('dashboard.permission_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating permission: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Remove the specified permission
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            Log::info('Permission deleted successfully', ['permission_id' => $id]);

            return redirect()->route('permissions.index')
                ->with('success', __('dashboard.permission_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting permission: '.$e->getMessage());
            return redirect()->back()
                ->with('error', __('dashboard.error_occurred'));
        }
    }
}

