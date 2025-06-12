<?php

namespace App\Exports;

use App\Models\CarImport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CarImportsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = CarImport::query();
        
        if ($this->startDate) {
            $query->whereDate('shipping_date', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('shipping_date', '<=', $this->endDate);
        }
        
        return $query->orderBy('shipping_date', 'desc');
    }

    public function headings(): array
    {
        return [
            '#',
            'الشركة المصنعة',
            'الطراز',
            'السنة',
            'رقم الشاسيه',
            'الحالة',
            'تاريخ الشحن',
            'تاريخ الوصول المتوقع',
            'تاريخ الوصول الفعلي',
            'تكلفة الاستيراد',
            'تكلفة التخليص',
            'التكلفة الإجمالية',
        ];
    }

    public function map($carImport): array
    {
        return [
            $carImport->id,
            $carImport->make,
            $carImport->model,
            $carImport->year,
            $carImport->vin,
            $carImport->status,
            $carImport->shipping_date->format('Y-m-d'),
            $carImport->expected_arrival_date->format('Y-m-d'),
            $carImport->actual_arrival_date ? $carImport->actual_arrival_date->format('Y-m-d') : 'لم يصل بعد',
            $carImport->import_cost,
            $carImport->customs_cost,
            $carImport->total_cost,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
