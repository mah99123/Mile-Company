@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-pie me-2"></i>
        تقرير الأرباح والخسائر
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.profit-loss', array_merge(request()->all(), ['export_pdf' => 1])) }}" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf me-1"></i>
            تصدير PDF
        </a>
        <a href="{{ route('reports.profit-loss', array_merge(request()->all(), ['export_excel' => 1])) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-1"></i>
            تصدير Excel
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
    </div>
</div>

<!-- Period Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.profit-loss') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $data['period']['from']) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $data['period']['to']) }}">
                </div>
                <div class="col-md-4">
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

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($data['revenue']['total'], 0) }}</div>
            <div class="stats-label">إجمالي الإيرادات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ number_format($data['expenses'], 0) }}</div>
            <div class="stats-label">إجمالي المصروفات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, {{ $data['net_profit'] >= 0 ? '#f093fb 0%, #f5576c 100%' : '#ff6b6b 0%, #ee5a52 100%' }});">
            <div class="stats-number">{{ number_format($data['net_profit'], 0) }}</div>
            <div class="stats-label">{{ $data['net_profit'] >= 0 ? 'صافي الربح' : 'صافي الخسارة' }} (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $data['expenses'] > 0 ? round(($data['net_profit'] / $data['revenue']['total']) * 100, 1) : 0 }}%</div>
            <div class="stats-label">هامش الربح</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">توزيع الإيرادات حسب المنصة</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">الإيرادات مقابل المصروفات</h5>
            </div>
            <div class="card-body">
                <canvas id="profitChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Breakdown -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-up me-2"></i>
                    الإيرادات
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>إيرادات منصة ميم (الحملات الإعلانية)</td>
                        <td class="text-end text-success">{{ number_format($data['revenue']['campaigns'], 2) }} دينار</td>
                    </tr>
                    <tr>
                        <td>إيرادات محمد فون تك (المبيعات)</td>
                        <td class="text-end text-success">{{ number_format($data['revenue']['sales'], 2) }} دينار</td>
                    </tr>
                    <tr>
                        <td>إيرادات استيراد السيارات</td>
                        <td class="text-end text-success">{{ number_format($data['revenue']['car_imports'], 2) }} دينار</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>إجمالي الإيرادات</strong></td>
                        <td class="text-end"><strong class="text-success">{{ number_format($data['revenue']['total'], 2) }} دينار</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-down me-2"></i>
                    المصروفات
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>الرواتب والأجور</td>
                        <td class="text-end text-danger">{{ number_format($data['expenses'] * 0.6, 2) }} دينار</td>
                    </tr>
                    <tr>
                        <td>الإيجارات والمرافق</td>
                        <td class="text-end text-danger">{{ number_format($data['expenses'] * 0.2, 2) }} دينار</td>
                    </tr>
                    <tr>
                        <td>مصروفات التسويق</td>
                        <td class="text-end text-danger">{{ number_format($data['expenses'] * 0.15, 2) }} دينار</td>
                    </tr>
                    <tr>
                        <td>مصروفات أخرى</td>
                        <td class="text-end text-danger">{{ number_format($data['expenses'] * 0.05, 2) }} دينار</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>إجمالي المصروفات</strong></td>
                        <td class="text-end"><strong class="text-danger">{{ number_format($data['expenses'], 2) }} دينار</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Net Profit Summary -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card {{ $data['net_profit'] >= 0 ? 'border-success' : 'border-danger' }}">
            <div class="card-header {{ $data['net_profit'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                <h5 class="card-title mb-0 text-center">
                    <i class="fas {{ $data['net_profit'] >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }} me-2"></i>
                    {{ $data['net_profit'] >= 0 ? 'صافي الربح' : 'صافي الخسارة' }}
                </h5>
            </div>
            <div class="card-body text-center">
                <h2 class="{{ $data['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($data['net_profit'], 2) }} دينار
                </h2>
                <p class="text-muted">
                    للفترة من {{ $data['period']['from'] }} إلى {{ $data['period']['to'] }}
                </p>
                
                @if($data['net_profit'] >= 0)
                    <div class="alert alert-success">
                        <i class="fas fa-thumbs-up me-2"></i>
                        تحقق ربح جيد خلال هذه الفترة! استمر في الأداء الممتاز.
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        هناك خسارة في هذه الفترة. يُنصح بمراجعة المصروفات وزيادة الإيرادات.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ['منصة ميم', 'محمد فون تك', 'استيراد السيارات'],
        datasets: [{
            label: 'الإيرادات (دينار)',
            data: [
                {{ $data['revenue']['campaigns'] }},
                {{ $data['revenue']['sales'] }},
                {{ $data['revenue']['car_imports'] }}
            ],
            backgroundColor: ['#667eea', '#11998e', '#f093fb']
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

// Profit Chart
const profitCtx = document.getElementById('profitChart').getContext('2d');
const profitChart = new Chart(profitCtx, {
    type: 'doughnut',
    data: {
        labels: ['الإيرادات', 'المصروفات'],
        datasets: [{
            data: [{{ $data['revenue']['total'] }}, {{ $data['expenses'] }}],
            backgroundColor: ['#28a745', '#dc3545']
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
