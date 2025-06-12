<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Sale::with(['product', 'user']);
        
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            '#',
            'المنتج',
            'السعر الوحدة',
            'الكمية',
            'الإجمالي',
            'البائع',
            'طريقة الدفع',
            'تاريخ البيع',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->product->name,
            $sale->unit_price,
            $sale->quantity,
            $sale->total_price,
            $sale->user->name,
            $sale->payment_method,
            $sale->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
