<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@multiplatform.com',
            'password' => Hash::make('password'),
            'phone' => '+964501234567',
            'department' => 'إدارة النظام',
            'status' => 'active',
        ]);
        $admin->assignRole('Super Admin');

        // Meym Manager
        $meymManager = User::create([
            'name' => 'مدير منصة ميم',
            'email' => 'meym@multiplatform.com',
            'password' => Hash::make('password'),
            'phone' => '+964501234568',
            'department' => 'التسويق الرقمي',
            'status' => 'active',
        ]);
        $meymManager->assignRole('Meym Manager');

        // PhoneTech Manager
        $phonetechManager = User::create([
            'name' => 'مدير محمد فون تك',
            'email' => 'phonetech@multiplatform.com',
            'password' => Hash::make('password'),
            'phone' => '+964501234569',
            'department' => 'المبيعات',
            'status' => 'active',
        ]);
        $phonetechManager->assignRole('PhoneTech Manager');

        // Car Import Manager
        $carImportManager = User::create([
            'name' => 'مدير استيراد السيارات',
            'email' => 'carimport@multiplatform.com',
            'password' => Hash::make('password'),
            'phone' => '+964501234570',
            'department' => 'الاستيراد والتصدير',
            'status' => 'active',
        ]);
        $carImportManager->assignRole('Car Import Manager');

        // Employee
        $employee = User::create([
            'name' => 'موظف عام',
            'email' => 'employee@multiplatform.com',
            'password' => Hash::make('password'),
            'phone' => '+964501234571',
            'department' => 'العمليات',
            'status' => 'active',
        ]);
        $employee->assignRole('Employee');
    }
}
