<?php

namespace App\Exports;

use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampaignsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Campaign::query();
        
        if ($this->startDate) {
            $query->where('start_date', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->where('end_date', '<=', $this->endDate);
        }
        
        return $query->orderBy('start_date', 'desc');
    }

    public function headings(): array
    {
        return [
            '#',
            'العنوان',
            'الوصف',
            'الحالة',
            'تاريخ البدء',
            'تاريخ الانتهاء',
            'الميزانية',
            'التكلفة الفعلية',
            'عدد التحديثات',
        ];
    }

    public function map($campaign): array
    {
        return [
            $campaign->id,
            $campaign->title,
            $campaign->description,
            $campaign->status,
            $campaign->start_date->format('Y-m-d'),
            $campaign->end_date->format('Y-m-d'),
            $campaign->budget,
            $campaign->actual_cost,
            $campaign->updates_count,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
