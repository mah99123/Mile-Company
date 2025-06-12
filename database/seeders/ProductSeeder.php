<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'آيفون 15 برو ماكس',
                'category' => 'Phone',
                'cost_price' => 4500.00,
                'selling_price' => 5299.00,
                'quantity_in_stock' => 25,
                'reorder_threshold' => 5,
                'sku' => 'IP15PM-256-BLK',
                'description' => 'هاتف آيفون 15 برو ماكس بسعة 256 جيجابايت، لون أسود، مع ضمان لمدة سنة',
                'supplier_id' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'سامسونج جالاكسي S23 ألترا',
                'category' => 'Phone',
                'cost_price' => 3800.00,
                'selling_price' => 4599.00,
                'quantity_in_stock' => 20,
                'reorder_threshold' => 5,
                'sku' => 'SGS23U-512-GRN',
                'description' => 'هاتف سامسونج جالاكسي S23 ألترا بسعة 512 جيجابايت، لون أخضر، مع ضمان لمدة سنة',
                'supplier_id' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'شاومي 13T برو',
                'category' => 'Phone',
                'cost_price' => 1800.00,
                'selling_price' => 2199.00,
                'quantity_in_stock' => 15,
                'reorder_threshold' => 3,
                'sku' => 'XM13TP-256-BLU',
                'description' => 'هاتف شاومي 13T برو بسعة 256 جيجابايت، لون أزرق، مع ضمان لمدة سنة',
                'supplier_id' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'سماعات آيربودز برو 2',
                'category' => 'Accessory',
                'cost_price' => 800.00,
                'selling_price' => 999.00,
                'quantity_in_stock' => 30,
                'reorder_threshold' => 10,
                'sku' => 'APP2-WHT',
                'description' => 'سماعات آيربودز برو 2 لاسلكية مع خاصية إلغاء الضوضاء، لون أبيض',
                'supplier_id' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'ساعة أبل ووتش سيريس 9',
                'category' => 'Accessory',
                'cost_price' => 1500.00,
                'selling_price' => 1899.00,
                'quantity_in_stock' => 12,
                'reorder_threshold' => 4,
                'sku' => 'AWS9-45-BLK',
                'description' => 'ساعة أبل ووتش سيريس 9 مقاس 45 ملم، لون أسود، مع ضمان لمدة سنة',
                'supplier_id' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'شاحن سامسونج سريع 45 واط',
                'category' => 'Accessory',
                'cost_price' => 80.00,
                'selling_price' => 149.00,
                'quantity_in_stock' => 50,
                'reorder_threshold' => 15,
                'sku' => 'SGFC-45W-WHT',
                'description' => 'شاحن سامسونج سريع بقوة 45 واط، لون أبيض، مع كابل USB-C',
                'supplier_id' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'تلفزيون سامسونج QLED 65 بوصة',
                'category' => 'Electronics',
                'cost_price' => 3500.00,
                'selling_price' => 4299.00,
                'quantity_in_stock' => 8,
                'reorder_threshold' => 2,
                'sku' => 'SGTV-QLED65-BLK',
                'description' => 'تلفزيون سامسونج QLED بحجم 65 بوصة، دقة 4K، مع ضمان لمدة سنتين',
                'supplier_id' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'لابتوب ماك بوك برو 16 بوصة',
                'category' => 'Electronics',
                'cost_price' => 7500.00,
                'selling_price' => 8999.00,
                'quantity_in_stock' => 6,
                'reorder_threshold' => 2,
                'sku' => 'MBP16-M2-512-SLV',
                'description' => 'لابتوب ماك بوك برو 16 بوصة، معالج M2 برو، سعة 512 جيجابايت، لون فضي',
                'supplier_id' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'سماعات سوني WH-1000XM5',
                'category' => 'Electronics',
                'cost_price' => 900.00,
                'selling_price' => 1299.00,
                'quantity_in_stock' => 10,
                'reorder_threshold' => 3,
                'sku' => 'SNY-WH1000XM5-BLK',
                'description' => 'سماعات سوني WH-1000XM5 لاسلكية مع خاصية إلغاء الضوضاء، لون أسود',
                'supplier_id' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'آيباد برو 12.9 بوصة',
                'category' => 'Electronics',
                'cost_price' => 3800.00,
                'selling_price' => 4599.00,
                'quantity_in_stock' => 4,
                'reorder_threshold' => 2,
                'sku' => 'IPADP-129-256-SLV',
                'description' => 'آيباد برو 12.9 بوصة، معالج M2، سعة 256 جيجابايت، لون فضي',
                'supplier_id' => 1,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
