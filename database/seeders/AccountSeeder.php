<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\GeneralJournalEntry;
use Carbon\Carbon;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        // Create accounts
        $accounts = [
            // Asset Accounts
            ['platform' => 'General', 'account_name' => 'الصندوق', 'balance' => 50000.00, 'account_type' => 'Asset'],
            ['platform' => 'General', 'account_name' => 'البنك الأهلي', 'balance' => 150000.00, 'account_type' => 'Asset'],
            ['platform' => 'General', 'account_name' => 'بنك الراجحي', 'balance' => 200000.00, 'account_type' => 'Asset'],
            ['platform' => 'Meym', 'account_name' => 'حساب منصة ميم', 'balance' => 75000.00, 'account_type' => 'Asset'],
            ['platform' => 'PhoneTech', 'account_name' => 'حساب محمد فون تك', 'balance' => 120000.00, 'account_type' => 'Asset'],
            ['platform' => 'CarImport', 'account_name' => 'حساب استيراد السيارات', 'balance' => 250000.00, 'account_type' => 'Asset'],
            ['platform' => 'General', 'account_name' => 'المخزون', 'balance' => 180000.00, 'account_type' => 'Asset'],
            ['platform' => 'General', 'account_name' => 'الأثاث والمعدات', 'balance' => 85000.00, 'account_type' => 'Asset'],
            
            // Liability Accounts
            ['platform' => 'General', 'account_name' => 'الدائنون', 'balance' => 45000.00, 'account_type' => 'Liability'],
            ['platform' => 'General', 'account_name' => 'قروض بنكية', 'balance' => 120000.00, 'account_type' => 'Liability'],
            ['platform' => 'PhoneTech', 'account_name' => 'دائنو محمد فون تك', 'balance' => 35000.00, 'account_type' => 'Liability'],
            ['platform' => 'CarImport', 'account_name' => 'دائنو استيراد السيارات', 'balance' => 80000.00, 'account_type' => 'Liability'],
            
            // Equity Accounts
            ['platform' => 'General', 'account_name' => 'رأس المال', 'balance' => 500000.00, 'account_type' => 'Equity'],
            ['platform' => 'General', 'account_name' => 'الأرباح المحتجزة', 'balance' => 150000.00, 'account_type' => 'Equity'],
            
            // Revenue Accounts
            ['platform' => 'Meym', 'account_name' => 'إيرادات منصة ميم', 'balance' => 0.00, 'account_type' => 'Revenue'],
            ['platform' => 'PhoneTech', 'account_name' => 'إيرادات محمد فون تك', 'balance' => 0.00, 'account_type' => 'Revenue'],
            ['platform' => 'CarImport', 'account_name' => 'إيرادات استيراد السيارات', 'balance' => 0.00, 'account_type' => 'Revenue'],
            
            // Expense Accounts
            ['platform' => 'Meym', 'account_name' => 'مصروفات منصة ميم', 'balance' => 0.00, 'account_type' => 'Expense'],
            ['platform' => 'PhoneTech', 'account_name' => 'مصروفات محمد فون تك', 'balance' => 0.00, 'account_type' => 'Expense'],
            ['platform' => 'CarImport', 'account_name' => 'مصروفات استيراد السيارات', 'balance' => 0.00, 'account_type' => 'Expense'],
            ['platform' => 'General', 'account_name' => 'الرواتب والأجور', 'balance' => 0.00, 'account_type' => 'Expense'],
            ['platform' => 'General', 'account_name' => 'الإيجارات', 'balance' => 0.00, 'account_type' => 'Expense'],
            ['platform' => 'General', 'account_name' => 'المرافق والخدمات', 'balance' => 0.00, 'account_type' => 'Expense'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }

        // Create sample journal entries
        $entries = [
            [
                'date' => Carbon::now()->subDays(30),
                'description' => 'إيرادات حملة إعلانية - عيادة الدكتور أحمد',
                'debit_account' => 4, // حساب منصة ميم
                'credit_account' => 15, // إيرادات منصة ميم
                'amount' => 15000.00,
                'reference_number' => 'JE-2023-001',
            ],
            [
                'date' => Carbon::now()->subDays(25),
                'description' => 'مصروفات إعلانات فيسبوك',
                'debit_account' => 18, // مصروفات منصة ميم
                'credit_account' => 4, // حساب منصة ميم
                'amount' => 5000.00,
                'reference_number' => 'JE-2023-002',
            ],
            [
                'date' => Carbon::now()->subDays(20),
                'description' => 'بيع هواتف آيفون',
                'debit_account' => 5, // حساب محمد فون تك
                'credit_account' => 16, // إيرادات محمد فون تك
                'amount' => 25000.00,
                'reference_number' => 'JE-2023-003',
            ],
            [
                'date' => Carbon::now()->subDays(15),
                'description' => 'شراء مخزون هواتف جديدة',
                'debit_account' => 7, // المخزون
                'credit_account' => 5, // حساب محمد فون تك
                'amount' => 18000.00,
                'reference_number' => 'JE-2023-004',
            ],
            [
                'date' => Carbon::now()->subDays(10),
                'description' => 'عمولة استيراد سيارات',
                'debit_account' => 6, // حساب استيراد السيارات
                'credit_account' => 17, // إيرادات استيراد السيارات
                'amount' => 35000.00,
                'reference_number' => 'JE-2023-005',
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'description' => 'دفع رواتب الموظفين',
                'debit_account' => 21, // الرواتب والأجور
                'credit_account' => 2, // البنك الأهلي
                'amount' => 45000.00,
                'reference_number' => 'JE-2023-006',
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'description' => 'دفع إيجار المكاتب',
                'debit_account' => 22, // الإيجارات
                'credit_account' => 3, // بنك الراجحي
                'amount' => 15000.00,
                'reference_number' => 'JE-2023-007',
            ],
        ];

        foreach ($entries as $entry) {
            GeneralJournalEntry::create($entry);
        }
    }
}
