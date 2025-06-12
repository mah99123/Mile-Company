@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-car me-2"></i>
        تقرير استيراد السيارات
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.car-imports', array_merge(request()->all(), ['export_pdf' => 1])) }}" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf me-1"></i>
            تصدير PDF
        </a>
        <a href="{{ route('reports.car-imports', array_merge(request()->all(), ['export_excel' => 1])) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-1"></i>
            تصدير Excel
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.car-imports') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">شركة المشتري</label>
                    <select class="form-select" name="buyer_company">
                        <option value="">جميع الشركات</option>
                        @foreach($carImports->pluck('buyer_company')->unique() as $company)
                            <option value="{{ $company }}" {{ request('buyer_company') == $company ? 'selected' : '' }}>
                                {{ $company }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-filter me-1"></i>
                        تطبيق الفلتر
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['total_imports'] }}</div>
            <div class="stats-label">إجمالي العمليات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($stats['total_profit'], 0) }}</div>
            <div class="stats-label">إجمالي الأرباح (دولار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $stats['by_status']['Delivered'] ?? 0 }}</div>
            <div class="stats-label">العمليات المكتملة</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $stats['by_status']['Pending'] ?? 0 }}</div>
            <div class="stats-label">العمليات المعلقة</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">الاستيراد الشهري</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyImportsChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">توزيع الحالات</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Company Performance -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">أداء الشركات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>شركة المشتري</th>
                                <th>عدد العمليات</th>
                                <th>إجمالي الأرباح</th>
                                <th>متوسط الربح</th>
                                <th>العمليات المكتملة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['by_company'] as $company => $count)
                            @php
                                $companyImports = $carImports->where('buyer_company', $company);
                                $totalProfit = $companyImports->sum('total_profit');
                                $avgProfit = $companyImports->avg('total_profit');
                                $completed = $companyImports->where('shipping_status', 'Delivered')->count();
                            @endphp
                            <tr>
                                <td><strong>{{ $company }}</strong></td>
                                <td>{{ $count }}</td>
                                <td class="text-success">{{ number_format($totalProfit, 0) }} دولار</td>
                                <td>{{ number_format($avgProfit, 0) }} دولار</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $count > 0 ? ($completed / $count) * 100 : 0 }}%">
                                            {{ $completed }}/{{ $count }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Car Imports Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تفاصيل عمليات الاستيراد</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم اللوت</th>
                        <th>نوع المزاد</th>
                        <th>شركة المشتري</th>
                        <th>المبلغ الإجمالي</th>
                        <th>إجمالي الربح</th>
                        <th>حالة الشحن</th>
                        <th>تاريخ المزاد</th>
                        <th>الموظف المسؤول</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carImports as $import)
                    <tr>
                        <td><strong>{{ $import->lot_number }}</strong></td>
                        <td><span class="badge bg-info">{{ $import->auction_type }}</span></td>
                        <td>{{ $import->buyer_company }}</td>
                        <td>{{ number_format($import->total_with_transfer, 2) }} {{ $import->currency }}</td>
                        <td class="text-success">{{ number_format($import->total_profit, 2) }} {{ $import->currency }}</td>
                        <td>
                            @switch($import->shipping_status)
                                @case('Pending')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                @case('Shipped')
                                    <span class="badge bg-primary">قيد الشحن</span>
                                    @break
                                @case('Arrived')
                                    <span class="badge bg-info">وصل</span>
                                    @break
                                @case('Delivered')
                                    <span class="badge bg-success">تم التسليم</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $import->auction_invoice_date->format('Y-m-d') }}</td>
                        <td>{{ $import->employee->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="3"><strong>الإجمالي</strong></td>
                        <td><strong>{{ number_format($carImports->sum('total_with_transfer'), 2) }} دولار</strong></td>
                        <td><strong>{{ number_format($carImports->sum('total_profit'), 2) }} دولار</strong></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Monthly Imports Chart
const monthlyImportsCtx = document.getElementById('monthlyImportsChart').getContext('2d');
const monthlyImportsChart = new Chart(monthlyImportsCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($stats['monthly_imports'] as $month => $count)
                '{{ $month }}',
            @endforeach
        ],
        datasets: [{
            label: 'عدد العمليات',
            data: [
                @foreach($stats['monthly_imports'] as $month => $count)
                    {{ $count }},
                @endforeach
            ],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($stats['by_status'] as $status => $count)
                '{{ $status }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($stats['by_status'] as $status => $count)
                    {{ $count }},
                @endforeach
            ],
            backgroundColor: ['#ffc107', '#007bff', '#17a2b8', '#28a745']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection
