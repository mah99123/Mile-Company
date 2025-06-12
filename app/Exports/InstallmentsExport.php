<?php

namespace App\Exports;

use App\Models\InstallmentPayment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstallmentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function query()
    {
        $query = InstallmentPayment::with(['sale.product', 'sale.user']);
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->orderBy('due_date');
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم المبيعة',
            'المنتج',
            'العميل',
            'المبلغ',
            'تاريخ الاستحقاق',
            'الحالة',
            'تاريخ الدفع',
            'طريقة الدفع',
        ];
    }

    public function map($installment): array
    {
        return [
            $installment->id,
            $installment->sale_id,
            $installment->sale->product->name,
            $installment->sale->customer_name,
            $installment->amount,
            $installment->due_date->format('Y-m-d'),
            $this->getStatusText($installment->status),
            $installment->payment_date ? $installment->payment_date->format('Y-m-d') : 'لم يتم الدفع',
            $installment->payment_method ?? 'غير محدد',
        ];
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 'paid':
                return 'مدفوع';
            case 'pending':
                return 'قيد الانتظار';
            case 'overdue':
                return 'متأخر';
            default:
                return $status;
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
