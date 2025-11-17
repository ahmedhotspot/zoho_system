<?php

/**
 * Quick script to assign super-admin role to a user
 * 
 * Usage:
 * php assign_super_admin.php <user_email>
 * 
 * Example:
 * php assign_super_admin.php admin@example.com
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Get email from command line argument
$email = $argv[1] ?? null;

if (!$email) {
    echo "❌ Error: Please provide user email\n";
    echo "Usage: php assign_super_admin.php <user_email>\n";
    echo "Example: php assign_super_admin.php admin@example.com\n";
    exit(1);
}

// Find user
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Error: User with email '{$email}' not found\n";
    echo "\nAvailable users:\n";
    $users = User::all();
    foreach ($users as $u) {
        echo "  - {$u->email} (ID: {$u->id}, Name: {$u->name})\n";
    }
    exit(1);
}

// Check if super-admin role exists
$superAdminRole = Role::where('name', 'super-admin')->first();

if (!$superAdminRole) {
    echo "❌ Error: super-admin role not found\n";
    echo "Please run: php artisan db:seed --class=RolesAndPermissionsSeeder\n";
    exit(1);
}

// Assign super-admin role
$user->syncRoles(['super-admin']);

echo "✅ Success! Super-admin role assigned to:\n";
echo "   Email: {$user->email}\n";
echo "   Name: {$user->name}\n";
echo "   ID: {$user->id}\n";
echo "\n";
echo "User now has the following roles:\n";
foreach ($user->roles as $role) {
    echo "  - {$role->name}\n";
}
echo "\n";
echo "User now has " . $user->getAllPermissions()->count() . " permissions\n";

