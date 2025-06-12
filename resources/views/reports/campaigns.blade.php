@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        تقرير الحملات الإعلانية
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.campaigns', array_merge(request()->all(), ['export_pdf' => 1])) }}" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf me-1"></i>
            تصدير PDF
        </a>
        <a href="{{ route('reports.campaigns', array_merge(request()->all(), ['export_excel' => 1])) }}" class="btn btn-success me-2">
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
        <form method="GET" action="{{ route('reports.campaigns') }}">
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
                    <label class="form-label">التخصص</label>
                    <select class="form-select" name="specialization">
                        <option value="">جميع التخصصات</option>
                        @foreach($campaigns->pluck('specialization')->unique() as $spec)
                            <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                {{ $spec }}
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
            <div class="stats-number">{{ $stats['total_campaigns'] }}</div>
            <div class="stats-label">إجمالي الحملات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($stats['total_budget'], 0) }}</div>
            <div class="stats-label">إجمالي الميزانية (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ round($stats['average_duration'], 1) }}</div>
            <div class="stats-label">متوسط مدة الحملة (يوم)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $stats['by_status']['active'] ?? 0 }}</div>
            <div class="stats-label">الحملات النشطة</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">الحملات حسب التخصص</h5>
            </div>
            <div class="card-body">
                <canvas id="specializationChart" height="100"></canvas>
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

<!-- Campaigns Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تفاصيل الحملات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم الصفحة</th>
                        <th>المالك</th>
                        <th>التخصص</th>
                        <th>الميزانية</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>المدة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                    <tr>
                        <td><strong>{{ $campaign->page_name }}</strong></td>
                        <td>{{ $campaign->owner_name }}</td>
                        <td>{{ $campaign->specialization }}</td>
                        <td>{{ number_format($campaign->budget_total, 2) }} دينار</td>
                        <td>{{ $campaign->start_date->format('Y-m-d') }}</td>
                        <td>{{ $campaign->end_date->format('Y-m-d') }}</td>
                        <td>{{ $campaign->duration }} يوم</td>
                        <td>
                            @switch($campaign->status)
                                @case('active')
                                    <span class="badge bg-success">نشطة</span>
                                    @break
                                @case('paused')
                                    <span class="badge bg-warning">متوقفة</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-primary">مكتملة</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">ملغية</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="3"><strong>الإجمالي</strong></td>
                        <td><strong>{{ number_format($campaigns->sum('budget_total'), 2) }} دينار</strong></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Specialization Chart
const specializationCtx = document.getElementById('specializationChart').getContext('2d');
const specializationChart = new Chart(specializationCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($stats['by_specialization'] as $spec => $count)
                '{{ $spec }}',
            @endforeach
        ],
        datasets: [{
            label: 'عدد الحملات',
            data: [
                @foreach($stats['by_specialization'] as $spec => $count)
                    {{ $count }},
                @endforeach
            ],
            backgroundColor: [
                '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'
            ]
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
            backgroundColor: ['#28a745', '#ffc107', '#007bff', '#dc3545']
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
