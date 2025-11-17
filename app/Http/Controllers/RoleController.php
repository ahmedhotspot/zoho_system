<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index(Request $request)
    {
        try {
            $query = Role::with('permissions')->orderBy('created_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $roles = $query->paginate($perPage);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $roles,
                ]);
            }

            return view('dashboard.role.index', compact('roles'));

        } catch (\Exception $e) {
            Log::error('Error fetching roles: '.$e->getMessage());

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
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            // Group permissions by module (first word before space)
            return explode(' ', $permission->name)[1] ?? 'other';
        });

        return view('dashboard.role.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.role_name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('dashboard.role_name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.role_name'), 'max' => 255]),
            'permissions.array' => __('validation.array', ['attribute' => __('dashboard.permissions')]),
            'permissions.*.exists' => __('validation.exists', ['attribute' => __('dashboard.permission')]),
        ]);

        try {
            $role = Role::create(['name' => $validated['name']]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            Log::info('Role created successfully', ['role_id' => $role->id, 'name' => $role->name]);

            return redirect()->route('roles.index')
                ->with('success', __('dashboard.role_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating role: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);

            $permissions = Permission::all()->groupBy(function($permission) {
                // Group permissions by module (first word before space)
                return explode(' ', $permission->name)[1] ?? 'other';
            });

            $rolePermissions = $role->permissions->pluck('id')->toArray();

            return view('dashboard.role.create', compact('role', 'permissions', 'rolePermissions'));

        } catch (\Exception $e) {
            Log::error('Error fetching role: '.$e->getMessage());
            return redirect()->route('roles.index')
                ->with('error', __('dashboard.role_not_found'));
        }
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.role_name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('dashboard.role_name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.role_name'), 'max' => 255]),
            'permissions.array' => __('validation.array', ['attribute' => __('dashboard.permissions')]),
            'permissions.*.exists' => __('validation.exists', ['attribute' => __('dashboard.permission')]),
        ]);

        try {
            $role->update(['name' => $validated['name']]);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            } else {
                $role->syncPermissions([]);
            }

            Log::info('Role updated successfully', ['role_id' => $role->id]);

            return redirect()->route('roles.index')
                ->with('success', __('dashboard.role_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating role: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Prevent deletion of super-admin role
            if ($role->name === 'super-admin') {
                return redirect()->back()
                    ->with('error', __('dashboard.cannot_delete_super_admin'));
            }

            $role->delete();

            Log::info('Role deleted successfully', ['role_id' => $id]);

            return redirect()->route('roles.index')
                ->with('success', __('dashboard.role_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting role: '.$e->getMessage());
            return redirect()->back()
                ->with('error', __('dashboard.error_occurred'));
        }
    }
}

