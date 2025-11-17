<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Invoice permissions
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',

            // Customer permissions
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',

            // Item permissions
            'view items',
            'create items',
            'edit items',
            'delete items',

            // Payment permissions
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',

            // Estimate permissions
            'view estimates',
            'create estimates',
            'edit estimates',
            'delete estimates',

            // Expense permissions
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',

            // Bill permissions
            'view bills',
            'create bills',
            'edit bills',
            'delete bills',

            // Account permissions
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',

            // Company permissions
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',

            // Financing Type permissions
            'view financing-types',
            'create financing-types',
            'edit financing-types',
            'delete financing-types',

            // Financing permissions
            'view financings',
            'create financings',
            'edit financings',
            'delete financings',

            // CRM Lead permissions
            'view crm-leads',
            'create crm-leads',
            'edit crm-leads',
            'delete crm-leads',
            'convert crm-leads',

            // CRM Contact permissions
            'view crm-contacts',
            'create crm-contacts',
            'edit crm-contacts',
            'delete crm-contacts',

            // CRM Deal permissions
            'view crm-deals',
            'create crm-deals',
            'edit crm-deals',
            'delete crm-deals',

            // CRM Account permissions
            'view crm-accounts',
            'create crm-accounts',
            'edit crm-accounts',
            'delete crm-accounts',

            // CRM Task permissions
            'view crm-tasks',
            'create crm-tasks',
            'edit crm-tasks',
            'delete crm-tasks',

            // CRM Call permissions
            'view crm-calls',
            'create crm-calls',
            'edit crm-calls',
            'delete crm-calls',

            // CRM Event permissions
            'view crm-events',
            'create crm-events',
            'edit crm-events',
            'delete crm-events',

            // CRM Note permissions
            'view crm-notes',
            'create crm-notes',
            'edit crm-notes',
            'delete crm-notes',

            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Permission permissions
            'view permissions',
            'assign permissions',

            // Settings
            'view settings',
            'edit settings',

            // Dashboard
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - has most permissions except role/permission management
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            // Zoho Books modules
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'view customers', 'create customers', 'edit customers', 'delete customers',
            'view items', 'create items', 'edit items', 'delete items',
            'view payments', 'create payments', 'edit payments', 'delete payments',
            'view estimates', 'create estimates', 'edit estimates', 'delete estimates',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view bills', 'create bills', 'edit bills', 'delete bills',
            'view accounts', 'create accounts', 'edit accounts', 'delete accounts',

            // Custom modules
            'view companies', 'create companies', 'edit companies', 'delete companies',
            'view financing-types', 'create financing-types', 'edit financing-types', 'delete financing-types',
            'view financings', 'create financings', 'edit financings', 'delete financings',

            // CRM modules
            'view crm-leads', 'create crm-leads', 'edit crm-leads', 'delete crm-leads', 'convert crm-leads',
            'view crm-contacts', 'create crm-contacts', 'edit crm-contacts', 'delete crm-contacts',
            'view crm-deals', 'create crm-deals', 'edit crm-deals', 'delete crm-deals',
            'view crm-accounts', 'create crm-accounts', 'edit crm-accounts', 'delete crm-accounts',
            'view crm-tasks', 'create crm-tasks', 'edit crm-tasks', 'delete crm-tasks',
            'view crm-calls', 'create crm-calls', 'edit crm-calls', 'delete crm-calls',
            'view crm-events', 'create crm-events', 'edit crm-events', 'delete crm-events',
            'view crm-notes', 'create crm-notes', 'edit crm-notes', 'delete crm-notes',

            // User management
            'view users', 'create users', 'edit users', 'delete users',

            // Settings & Dashboard
            'view settings', 'view dashboard',
        ]);

        // Manager - can view and create, limited edit/delete
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            // Zoho Books modules - view, create, edit (no delete)
            'view invoices', 'create invoices', 'edit invoices',
            'view customers', 'create customers', 'edit customers',
            'view items', 'create items', 'edit items',
            'view payments', 'create payments', 'edit payments',
            'view estimates', 'create estimates', 'edit estimates',
            'view expenses', 'create expenses', 'edit expenses',
            'view bills', 'create bills', 'edit bills',
            'view accounts', 'create accounts', 'edit accounts',

            // Custom modules
            'view companies', 'view financing-types',
            'view financings', 'create financings', 'edit financings',

            // CRM modules - view, create, edit (no delete)
            'view crm-leads', 'create crm-leads', 'edit crm-leads',
            'view crm-contacts', 'create crm-contacts', 'edit crm-contacts',
            'view crm-deals', 'create crm-deals', 'edit crm-deals',
            'view crm-accounts', 'create crm-accounts', 'edit crm-accounts',
            'view crm-tasks', 'create crm-tasks', 'edit crm-tasks',
            'view crm-calls', 'create crm-calls', 'edit crm-calls',
            'view crm-events', 'create crm-events', 'edit crm-events',
            'view crm-notes', 'create crm-notes', 'edit crm-notes',

            // User management - view only
            'view users',

            // Dashboard
            'view dashboard',
        ]);

        // Employee - can only view and create (no edit/delete)
        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            // Zoho Books modules - view and create only
            'view invoices', 'create invoices',
            'view customers', 'create customers',
            'view items', 'create items',
            'view payments', 'create payments',
            'view estimates', 'create estimates',
            'view expenses', 'create expenses',
            'view bills', 'create bills',
            'view accounts',

            // Custom modules
            'view companies', 'view financing-types',
            'view financings', 'create financings',

            // CRM modules - view and create only
            'view crm-leads', 'create crm-leads',
            'view crm-contacts', 'create crm-contacts',
            'view crm-deals', 'create crm-deals',
            'view crm-accounts', 'create crm-accounts',
            'view crm-tasks', 'create crm-tasks',
            'view crm-calls', 'create crm-calls',
            'view crm-events', 'create crm-events',
            'view crm-notes', 'create crm-notes',

            // Dashboard
            'view dashboard',
        ]);

        // Viewer - read-only access to everything
        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo([
            // Zoho Books modules - view only
            'view invoices',
            'view customers',
            'view items',
            'view payments',
            'view estimates',
            'view expenses',
            'view bills',
            'view accounts',

            // Custom modules - view only
            'view companies',
            'view financing-types',
            'view financings',

            // CRM modules - view only
            'view crm-leads',
            'view crm-contacts',
            'view crm-deals',
            'view crm-accounts',
            'view crm-tasks',
            'view crm-calls',
            'view crm-events',
            'view crm-notes',

            // Dashboard
            'view dashboard',
        ]);

        // Assign super-admin role to first user if exists
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('super-admin');
            $this->command->info('Assigned super-admin role to: ' . $firstUser->email);
        }

        $this->command->info('Roles and permissions created successfully!');
    }
}

