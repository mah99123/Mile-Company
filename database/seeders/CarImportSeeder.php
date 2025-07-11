<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarImport;
use Carbon\Carbon;

class CarImportSeeder extends Seeder
{
    public function run(): void
    {
        $carImports = [
            [
                'auction_type' => 'Copart',
                'lot_number' => 'CP-12345678',
                'total_with_transfer' => 45000.00,
                'amount_received' => 30000.00,
                'remaining_amount' => 15000.00,
                'auction_invoice_date' => Carbon::now()->subDays(30),
                'auction_invoice_number' => 'INV-CP-12345',
                'office_contract_number' => 'CONT-2023-001',
                'office_contract_date' => Carbon::now()->subDays(28),
                'office_invoice_amount' => 5000.00,
                'company_shipping_cost' => 8000.00,
                'customer_shipping_cost' => 10000.00,
                'remaining_office_invoice' => 2000.00,
                'shipping_profit' => 2000.00,
                'office_commission' => 1500.00,
                'currency' => 'IQD',
                'notes' => 'سيارة تويوتا كامري 2022 بحالة ممتازة',
                'employee_assigned' => 4,
                'shipping_status' => 'Shipped',
                'shipping_company' => 'الشركة العالمية للشحن',
                'buyer_company' => 'معرض السيارات الحديثة',
                'pull_date' => Carbon::now()->subDays(25),
                'shipping_date' => Carbon::now()->subDays(20),
                'arrival_date' => Carbon::now()->addDays(10),
                'container_number' => 'CONT-987654',
                'recipient_name' => 'فهد العتيبي',
                'recipient_receive_date' => null,
                'recipient_phone' => '+964501234567',
            ],
            [
                'auction_type' => 'IAAI',
                'lot_number' => 'IAAI-87654321',
                'total_with_transfer' => 60000.00,
                'amount_received' => 60000.00,
                'remaining_amount' => 0.00,
                'auction_invoice_date' => Carbon::now()->subDays(45),
                'auction_invoice_number' => 'INV-IAAI-54321',
                'office_contract_number' => 'CONT-2023-002',
                'office_contract_date' => Carbon::now()->subDays(43),
                'office_invoice_amount' => 6000.00,
                'company_shipping_cost' => 9000.00,
                'customer_shipping_cost' => 12000.00,
                'remaining_office_invoice' => 0.00,
                'shipping_profit' => 3000.00,
                'office_commission' => 2000.00,
                'currency' => 'IQD',
                'notes' => 'سيارة لكزس ES 2023 بحالة ممتازة',
                'employee_assigned' => 4,
                'shipping_status' => 'Arrived',
                'shipping_company' => 'شركة النقل السريع',
                'buyer_company' => 'معرض الفخامة للسيارات',
                'pull_date' => Carbon::now()->subDays(40),
                'shipping_date' => Carbon::now()->subDays(35),
                'arrival_date' => Carbon::now()->subDays(5),
                'container_number' => 'CONT-123456',
                'recipient_name' => 'خالد السعيد',
                'recipient_receive_date' => null,
                'recipient_phone' => '+964501234568',
            ],
            [
                'auction_type' => 'Manheim',
                'lot_number' => 'MNH-98765432',
                'total_with_transfer' => 80000.00,
                'amount_received' => 40000.00,
                'remaining_amount' => 40000.00,
                'auction_invoice_date' => Carbon::now()->subDays(15),
                'auction_invoice_number' => 'INV-MNH-67890',
                'office_contract_number' => 'CONT-2023-003',
                'office_contract_date' => Carbon::now()->subDays(14),
                'office_invoice_amount' => 7000.00,
                'company_shipping_cost' => 10000.00,
                'customer_shipping_cost' => 13000.00,
                'remaining_office_invoice' => 3000.00,
                'shipping_profit' => 3000.00,
                'office_commission' => 2500.00,
                'currency' => 'IQD',
                'notes' => 'سيارة جي إم سي يوكون 2023 بحالة ممتازة',
                'employee_assigned' => 4,
                'shipping_status' => 'Pending',
                'shipping_company' => 'شركة الخليج للشحن',
                'buyer_company' => 'معرض الرياض للسيارات',
                'pull_date' => Carbon::now()->subDays(10),
                'shipping_date' => null,
                'arrival_date' => null,
                'container_number' => null,
                'recipient_name' => 'محمد القحطاني',
                'recipient_receive_date' => null,
                'recipient_phone' => '+964501234569',
            ],
            [
                'auction_type' => 'Copart',
                'lot_number' => 'CP-56781234',
                'total_with_transfer' => 55000.00,
                'amount_received' => 55000.00,
                'remaining_amount' => 0.00,
                'auction_invoice_date' => Carbon::now()->subDays(60),
                'auction_invoice_number' => 'INV-CP-67890',
                'office_contract_number' => 'CONT-2023-004',
                'office_contract_date' => Carbon::now()->subDays(58),
                'office_invoice_amount' => 5500.00,
                'company_shipping_cost' => 8500.00,
                'customer_shipping_cost' => 11000.00,
                'remaining_office_invoice' => 0.00,
                'shipping_profit' => 2500.00,
                'office_commission' => 1800.00,
                'currency' => 'IQD',
                'notes' => 'سيارة فورد F-150 2022 بحالة ممتازة',
                'employee_assigned' => 4,
                'shipping_status' => 'Delivered',
                'shipping_company' => 'الشركة العالمية للشحن',
                'buyer_company' => 'معرض الأمير للسيارات',
                'pull_date' => Carbon::now()->subDays(55),
                'shipping_date' => Carbon::now()->subDays(50),
                'arrival_date' => Carbon::now()->subDays(20),
                'container_number' => 'CONT-456789',
                'recipient_name' => 'سعود المطيري',
                'recipient_receive_date' => Carbon::now()->subDays(18),
                'recipient_phone' => '+964501234570',
            ],
            [
                'auction_type' => 'IAAI',
                'lot_number' => 'IAAI-12348765',
                'total_with_transfer' => 70000.00,
                'amount_received' => 35000.00,
                'remaining_amount' => 35000.00,
                'auction_invoice_date' => Carbon::now()->subDays(20),
                'auction_invoice_number' => 'INV-IAAI-12345',
                'office_contract_number' => 'CONT-2023-005',
                'office_contract_date' => Carbon::now()->subDays(18),
                'office_invoice_amount' => 6500.00,
                'company_shipping_cost' => 9500.00,
                'customer_shipping_cost' => 12500.00,
                'remaining_office_invoice' => 3000.00,
                'shipping_profit' => 3000.00,
                'office_commission' => 2200.00,
                'currency' => 'IQD',
                'notes' => 'سيارة دودج تشارجر 2023 بحالة ممتازة',
                'employee_assigned' => 4,
                'shipping_status' => 'Shipped',
                'shipping_company' => 'شركة النقل السريع',
                'buyer_company' => 'معرض الخليج للسيارات',
                'pull_date' => Carbon::now()->subDays(15),
                'shipping_date' => Carbon::now()->subDays(10),
                'arrival_date' => Carbon::now()->addDays(20),
                'container_number' => 'CONT-789012',
                'recipient_name' => 'عبدالله الشمري',
                'recipient_receive_date' => null,
                'recipient_phone' => '+964501234571',
            ],
        ];

        foreach ($carImports as $carImport) {
            CarImport::create($carImport);
        }
    }
}
