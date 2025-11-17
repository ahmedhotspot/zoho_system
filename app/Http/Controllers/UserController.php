<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        try {
            $query = User::with('roles')->orderBy('created_at', 'desc');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by role
            if ($request->filled('role')) {
                $query->role($request->role);
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $users = $query->paginate($perPage);

            // Get all roles for filter dropdown
            $roles = Role::all();

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $users,
                ]);
            }

            return view('dashboard.user.index', compact('users', 'roles'));

        } catch (\Exception $e) {
            Log::error('Error fetching users: '.$e->getMessage());

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
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.user.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.name'), 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => __('dashboard.email')]),
            'email.email' => __('validation.email', ['attribute' => __('dashboard.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('dashboard.email')]),
            'password.required' => __('validation.required', ['attribute' => __('dashboard.password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('dashboard.password')]),
            'roles.array' => __('validation.array', ['attribute' => __('dashboard.roles')]),
            'roles.*.exists' => __('validation.exists', ['attribute' => __('dashboard.role')]),
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign roles
            if (!empty($validated['roles'])) {
                $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
                $user->assignRole($roleNames);
            }

            return redirect()->route('users.index')
                ->with('success', __('dashboard.user_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating user: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::all();
            $userRoles = $user->roles->pluck('id')->toArray();

            return view('dashboard.user.create', compact('user', 'roles', 'userRoles'));

        } catch (\Exception $e) {
            Log::error('Error fetching user: '.$e->getMessage());
            return redirect()->route('users.index')
                ->with('error', __('dashboard.user_not_found'));
        }
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ], [
            'name.required' => __('validation.required', ['attribute' => __('dashboard.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('dashboard.name'), 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => __('dashboard.email')]),
            'email.email' => __('validation.email', ['attribute' => __('dashboard.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('dashboard.email')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('dashboard.password')]),
            'roles.array' => __('validation.array', ['attribute' => __('dashboard.roles')]),
            'roles.*.exists' => __('validation.exists', ['attribute' => __('dashboard.role')]),
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            // Sync roles
            if (isset($validated['roles'])) {
                $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            } else {
                $user->syncRoles([]);
            }

            return redirect()->route('users.index')
                ->with('success', __('dashboard.user_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.error_occurred'));
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting current user
            if ($user->id === auth()->id()) {
                return redirect()->back()
                    ->with('error', __('dashboard.cannot_delete_yourself'));
            }

            // Prevent deleting super-admin
            if ($user->hasRole('super-admin')) {
                return redirect()->back()
                    ->with('error', __('dashboard.cannot_delete_super_admin_user'));
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', __('dashboard.user_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting user: '.$e->getMessage());
            return redirect()->back()
                ->with('error', __('dashboard.error_occurred'));
        }
    }
}

