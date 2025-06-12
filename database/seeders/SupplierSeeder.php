<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'شركة الاتصالات المتقدمة',
                'contact_person' => 'أحمد محمد',
                'phone' => '+964501234001',
                'address' => 'الرياض، حي العليا، شارع التخصصي',
                'email' => 'ahmed@advcomm.com',
                'status' => 'active',
            ],
            [
                'name' => 'مؤسسة التقنية الحديثة',
                'contact_person' => 'خالد العتيبي',
                'phone' => '+964501234002',
                'address' => 'جدة، حي الروضة، شارع الأمير سلطان',
                'email' => 'khalid@moderntech.com',
                'status' => 'active',
            ],
            [
                'name' => 'شركة الإلكترونيات العالمية',
                'contact_person' => 'سارة الشمري',
                'phone' => '+964501234003',
                'address' => 'الدمام، حي الشاطئ، طريق الملك فهد',
                'email' => 'sarah@globalelec.com',
                'status' => 'active',
            ],
            [
                'name' => 'مؤسسة الهواتف الذكية',
                'contact_person' => 'محمد القحطاني',
                'phone' => '+964501234004',
                'address' => 'الرياض، حي الملز، شارع الصناعة',
                'email' => 'mohammed@smartphones.com',
                'status' => 'active',
            ],
            [
                'name' => 'شركة الاستيراد الدولية',
                'contact_person' => 'فهد السعيد',
                'phone' => '+964501234005',
                'address' => 'جدة، حي البغدادية، شارع التجارة',
                'email' => 'fahad@importco.com',
                'status' => 'inactive',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
