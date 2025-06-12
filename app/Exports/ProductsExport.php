<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return Product::with('supplier')->orderBy('name');
    }

    public function headings(): array
    {
        return [
            '#',
            'اسم المنتج',
            'الوصف',
            'سعر التكلفة',
            'سعر البيع',
            'الكمية المتوفرة',
            'المورد',
            'تاريخ الإضافة',
            'آخر تحديث',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->cost_price,
            $product->selling_price,
            $product->stock_quantity,
            $product->supplier ? $product->supplier->name : 'غير محدد',
            $product->created_at->format('Y-m-d'),
            $product->updated_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
