@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-boxes me-2"></i>
        تقرير المخزون
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.inventory', ['export_pdf' => 1]) }}" class="btn btn-danger me-2">
            <i class="fas fa-file-pdf me-1"></i>
            تصدير PDF
        </a>
        <a href="{{ route('reports.inventory', ['export_excel' => 1]) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-1"></i>
            تصدير Excel
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقارير
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['total_products'] }}</div>
            <div class="stats-label">إجمالي المنتجات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($stats['total_value'], 0) }}</div>
            <div class="stats-label">قيمة المخزون (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $stats['low_stock'] }}</div>
            <div class="stats-label">منتجات قليلة المخزون</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $stats['by_category']->count() }}</div>
            <div class="stats-label">الفئات المتوفرة</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">المنتجات حسب الفئة</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">حالة المخزون</h5>
            </div>
            <div class="card-body">
                <canvas id="stockStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@if($stats['low_stock'] > 0)
<div class="alert alert-warning mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
        <div>
            <h5 class="alert-heading">تنبيه: منتجات قليلة المخزون</h5>
            <p class="mb-0">يوجد {{ $stats['low_stock'] }} منتج بحاجة إلى إعادة تموين. يرجى مراجعة قائمة المنتجات أدناه.</p>
        </div>
    </div>
</div>
@endif

<!-- Products Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">تفاصيل المخزون</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>المورد</th>
                        <th>الكمية المتوفرة</th>
                        <th>الحد الأدنى</th>
                        <th>سعر التكلفة</th>
                        <th>سعر البيع</th>
                        <th>قيمة المخزون</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="{{ $product->is_low_stock ? 'table-warning' : '' }}">
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->supplier->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $product->quantity_in_stock > $product->minimum_stock_level ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->quantity_in_stock }}
                            </span>
                        </td>
                        <td>{{ $product->minimum_stock_level }}</td>
                        <td>{{ number_format($product->cost_price, 0) }} دينار</td>
                        <td>{{ number_format($product->selling_price, 0) }} دينار</td>
                        <td class="text-success">{{ number_format($product->quantity_in_stock * $product->cost_price, 0) }} دينار</td>
                        <td>
                            @if($product->is_low_stock)
                                <span class="badge bg-warning">مخزون قليل</span>
                            @elseif($product->quantity_in_stock == 0)
                                <span class="badge bg-danger">نفد المخزون</span>
                            @else
                                <span class="badge bg-success">متوفر</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="7"><strong>الإجمالي</strong></td>
                        <td><strong>{{ number_format($stats['total_value'], 0) }} دينار</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Category Analysis -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">تحليل الفئات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الفئة</th>
                                <th>عدد المنتجات</th>
                                <th>إجمالي الكمية</th>
                                <th>قيمة المخزون</th>
                                <th>متوسط سعر البيع</th>
                                <th>المنتجات قليلة المخزون</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['by_category'] as $category => $count)
                            @php
                                $categoryProducts = $products->where('category', $category);
                                $totalQuantity = $categoryProducts->sum('quantity_in_stock');
                                $totalValue = $categoryProducts->sum(function($p) { return $p->quantity_in_stock * $p->cost_price; });
                                $avgPrice = $categoryProducts->avg('selling_price');
                                $lowStock = $categoryProducts->filter->is_low_stock->count();
                            @endphp
                            <tr>
                                <td><strong>{{ $category }}</strong></td>
                                <td>{{ $count }}</td>
                                <td>{{ $totalQuantity }}</td>
                                <td>{{ number_format($totalValue, 0) }} دينار</td>
                                <td>{{ number_format($avgPrice, 0) }} دينار</td>
                                <td>
                                    @if($lowStock > 0)
                                        <span class="badge bg-warning">{{ $lowStock }}</span>
                                    @else
                                        <span class="badge bg-success">0</span>
                                    @endif
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

@push('scripts')
<script>
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($stats['by_category'] as $category => $count)
                '{{ $category }}',
            @endforeach
        ],
        datasets: [{
            label: 'عدد المنتجات',
            data: [
                @foreach($stats['by_category'] as $category => $count)
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

// Stock Status Chart
const stockStatusCtx = document.getElementById('stockStatusChart').getContext('2d');
const stockStatusChart = new Chart(stockStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['متوفر', 'مخزون قليل', 'نفد المخزون'],
        datasets: [{
            data: [
                {{ $products->filter(function($p) { return $p->quantity_in_stock > $p->minimum_stock_level; })->count() }},
                {{ $products->filter(function($p) { return $p->is_low_stock && $p->quantity_in_stock > 0; })->count() }},
                {{ $products->where('quantity_in_stock', 0)->count() }}
            ],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
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
