@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        تقرير المبيعات والأقساط
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.sales', array_merge(request()->all(), ['export_pdf' => 1])) }}" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf me-1"></i>
            تصدير PDF
        </a>
        <a href="{{ route('reports.sales', array_merge(request()->all(), ['export_excel' => 1])) }}" class="btn btn-success me-2">
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
        <form method="GET" action="{{ route('reports.sales') }}">
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
                    <label class="form-label">حالة الفاتورة</label>
                    <select class="form-select" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>نشط</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>متأخر</option>
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
            <div class="stats-number">{{ $stats['total_sales'] }}</div>
            <div class="stats-label">إجمالي المبيعات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stats-label">إجمالي الإيرادات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ number_format($stats['total_profit'], 0) }}</div>
            <div class="stats-label">إجمالي الربح (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $stats['by_status']['Active'] ?? 0 }}</div>
            <div class="stats-label">الفواتير النشطة</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">المبيعات الشهرية</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlySalesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">توزيع حالات الفواتير</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Installments Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ملخص الأقساط</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h4 class="text-primary">{{ number_format($sales->sum('remaining_amount'), 0) }}</h4>
                        <small class="text-muted">إجمالي المبالغ المتبقية (دينار)</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-success">{{ $sales->where('status', 'Active')->count() }}</h4>
                        <small class="text-muted">عدد الفواتير النشطة</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-warning">{{ $sales->where('is_overdue', true)->count() }}</h4>
                        <small class="text-muted">الفواتير المتأخرة</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-info">{{ round($sales->avg('installment_period_months'), 1) }}</h4>
                        <small class="text-muted">متوسط فترة التقسيط (شهر)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تفاصيل المبيعات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>المبلغ الإجمالي</th>
                        <th>المبلغ المدفوع</th>
                        <th>المبلغ المتبقي</th>
                        <th>الأقساط المدفوعة</th>
                        <th>الحالة</th>
                        <th>تاريخ البيع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr class="{{ $sale->is_overdue ? 'table-warning' : '' }}">
                        <td><strong>{{ $sale->invoice_id }}</strong></td>
                        <td>{{ $sale->customer_name }}</td>
                        <td>{{ $sale->product->name ?? 'منتج محذوف' }}</td>
                        <td>{{ number_format($sale->total_amount, 2) }} دينار</td>
                        <td class="text-success">{{ number_format($sale->total_amount - $sale->remaining_amount, 2) }} دينار</td>
                        <td class="{{ $sale->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($sale->remaining_amount, 2) }} دينار
                        </td>
                        <td>{{ $sale->installments_paid }}/{{ $sale->installment_period_months }}</td>
                        <td>
                            @switch($sale->status)
                                @case('Pending')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                @case('Active')
                                    <span class="badge bg-primary">نشط</span>
                                    @break
                                @case('Completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                                @case('Overdue')
                                    <span class="badge bg-danger">متأخر</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="3"><strong>الإجمالي</strong></td>
                        <td><strong>{{ number_format($sales->sum('total_amount'), 2) }} دينار</strong></td>
                        <td><strong>{{ number_format($sales->sum(function($sale) { return $sale->total_amount - $sale->remaining_amount; }), 2) }} دينار</strong></td>
                        <td><strong>{{ number_format($sales->sum('remaining_amount'), 2) }} دينار</strong></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Monthly Sales Chart
const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesChart = new Chart(monthlySalesCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($stats['monthly_sales'] as $month => $total)
                '{{ $month }}',
            @endforeach
        ],
        datasets: [{
            label: 'المبيعات الشهرية',
            data: [
                @foreach($stats['monthly_sales'] as $month => $total)
                    {{ $total }},
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
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('ar-SA') + ' دينار';
                    }
                }
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
            backgroundColor: ['#ffc107', '#007bff', '#28a745', '#dc3545']
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
