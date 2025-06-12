<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin Role
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Meym Manager
        $meymManager = Role::create(['name' => 'Meym Manager']);
        $meymManager->givePermissionTo([
            'access meym',
            'create campaigns',
            'edit campaigns',
            'delete campaigns',
            'view campaigns',
            'view reports',
        ]);

        // PhoneTech Manager
        $phonetechManager = Role::create(['name' => 'PhoneTech Manager']);
        $phonetechManager->givePermissionTo([
            'access phonetech',
            'manage products',
            'manage sales',
            'manage suppliers',
            'view sales reports',
            'view reports',
        ]);

        // Car Import Manager
        $carImportManager = Role::create(['name' => 'Car Import Manager']);
        $carImportManager->givePermissionTo([
            'access carimport',
            'manage car imports',
            'view car import reports',
            'view reports',
        ]);

        // Employee
        $employee = Role::create(['name' => 'Employee']);
        $employee->givePermissionTo([
            'access meym',
            'view campaigns',
            'access phonetech',
            'manage sales',
            'access carimport',
            'manage car imports',
        ]);
    }
}
