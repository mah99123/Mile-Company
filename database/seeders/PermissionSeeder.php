<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Meym Platform
            ['name' => 'access meym', 'module' => 'meym'],
            ['name' => 'create campaigns', 'module' => 'meym'],
            ['name' => 'edit campaigns', 'module' => 'meym'],
            ['name' => 'delete campaigns', 'module' => 'meym'],
            ['name' => 'view campaigns', 'module' => 'meym'],

            // PhoneTech Platform
            ['name' => 'access phonetech', 'module' => 'phonetech'],
            ['name' => 'manage products', 'module' => 'phonetech'],
            ['name' => 'manage sales', 'module' => 'phonetech'],
            ['name' => 'manage suppliers', 'module' => 'phonetech'],
            ['name' => 'view sales reports', 'module' => 'phonetech'],

            // Car Import Platform
            ['name' => 'access carimport', 'module' => 'carimport'],
            ['name' => 'manage car imports', 'module' => 'carimport'],
            ['name' => 'view car import reports', 'module' => 'carimport'],

            // Admin
            ['name' => 'access admin', 'module' => 'admin'],
            ['name' => 'manage users', 'module' => 'admin'],
            ['name' => 'manage roles', 'module' => 'admin'],
            ['name' => 'manage permissions', 'module' => 'admin'],

            // Reports
            ['name' => 'view reports', 'module' => 'reports'],
            ['name' => 'export reports', 'module' => 'reports'],
            ['name' => 'view financial reports', 'module' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['module' => $permission['module']]
            );
        }
    }
}
