<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Permission;
use Modules\Core\Entities\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'Can view users list'],
            ['name' => 'users.create', 'display_name' => 'Create User', 'description' => 'Can create new users'],
            ['name' => 'users.update', 'display_name' => 'Update User', 'description' => 'Can update users'],
            ['name' => 'users.delete', 'display_name' => 'Delete User', 'description' => 'Can delete users'],

            // Roles
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'Can view roles list'],
            ['name' => 'roles.create', 'display_name' => 'Create Role', 'description' => 'Can create new roles'],
            ['name' => 'roles.update', 'display_name' => 'Update Role', 'description' => 'Can update roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Role', 'description' => 'Can delete roles'],

            // Permissions
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'description' => 'Can view permissions list'],
            ['name' => 'permissions.manage', 'display_name' => 'Manage Permissions', 'description' => 'Can assign/revoke permissions'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'Can view settings'],
            ['name' => 'settings.update', 'display_name' => 'Update Settings', 'description' => 'Can update settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all()->pluck('id');
            $adminRole->permissions()->sync($allPermissions);
            $this->command->info('Admin role assigned all permissions!');
        }
    }
}
