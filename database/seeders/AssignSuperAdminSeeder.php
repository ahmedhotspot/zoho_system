<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user
        $user = User::first();

        if (!$user) {
            $this->command->error("No users found in the database!");
            return;
        }

        // Check if super-admin role exists
        $superAdminRole = Role::where('name', 'super-admin')->first();

        if (!$superAdminRole) {
            $this->command->error("super-admin role not found!");
            return;
        }

        $user->syncRoles(['super-admin']);

        $this->command->info("Success");


    }
}
