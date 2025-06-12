@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 animate__animated animate__fadeInRight">
                        <i class="fas fa-chart-line text-warning ms-2"></i>
                        لوحة التقارير والإحصائيات
                    </h1>
                    <p class="text-muted mb-0">تقارير شاملة لجميع المنصات والعمليات التجارية</p>
                </div>
                <div class="btn-toolbar">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print ms-1"></i>
                            طباعة
                        </button>
                        <button type="button" class="btn btn-outline-success" id="exportExcel">
                            <i class="fas fa-file-excel ms-1"></i>
                            Excel
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="exportPDF">
                            <i class="fas fa-file-pdf ms-1"></i>
                            PDF
                        </button>
                    </div>
                    <button type="button" class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                        <i class="fas fa-calendar ms-1"></i>
                        تحديد الفترة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="stats-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stats-number">{{ number_format($campaignCount) }}</div>
                <div class="stats-label">الحملات الإعلانية</div>
                <div class="mt-3">
                    <a href="{{ route('reports.campaigns') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card success animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="stats-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="stats-number">{{ number_format($productCount) }}</div>
                <div class="stats-label">المنتجات</div>
                <div class="mt-3">
                    <a href="{{ route('reports.inventory') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card gold animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="stats-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-number">{{ number_format($saleCount) }}</div>
                <div class="stats-label">المبيعات</div>
                <div class="mt-3">
                    <a href="{{ route('reports.sales') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card silver animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="stats-icon">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stats-number">{{ number_format($carImportCount) }}</div>
                <div class="stats-label">استيراد السيارات</div>
                <div class="mt-3">
                    <a href="{{ route('reports.car-imports') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Sales Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                <div class="card-header">
                    <i class="fas fa-chart-line ms-2"></i>
                    المبيعات الشهرية - محمد فون تك
                </div>
                <div class="card-body">
                    <div id="salesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Installment Status Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <div class="card-header">
                    <i class="fas fa-chart-pie ms-2"></i>
                    حالة التقسيط
                </div>
                <div class="card-body">
                    <div id="installmentChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="row mb-4">
        <!-- Campaign Performance -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                <div class="card-header">
                    <i class="fas fa-bullhorn ms-2"></i>
                    أداء الحملات الإعلانية
                </div>
                <div class="card-body">
                    <div id="campaignChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
                <div class="card-header">
                    <i class="fas fa-boxes ms-2"></i>
                    حالة المخزون
                </div>
                <div class="card-body">
                    <div id="inventoryChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Sales -->
        <div class="col-md-4 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
                <div class="card-header">
                    <i class="fas fa-shopping-cart ms-2"></i>
                    أحدث المبيعات
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentSales as $sale)
                        <a href="{{ route('phonetech.sales.show', $sale) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $sale->invoice_id }}</h6>
                                <small>{{ $sale->sale_date->format('Y-m-d') }}</small>
                            </div>
                            <p class="mb-1">{{ $sale->customer_name }} - {{ $sale->product->name ?? 'منتج محذوف' }}</p>
                            <small class="text-muted">{{ number_format($sale->total_amount, 2) }} ر.س</small>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            <p class="mb-0">لا توجد مبيعات حديثة</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('phonetech.sales.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض الكل
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Campaigns -->
        <div class="col-md-4 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 1s;">
                <div class="card-header">
                    <i class="fas fa-bullhorn ms-2"></i>
                    أحدث الحملات الإعلانية
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentCampaigns as $campaign)
                        <a href="{{ route('meym.campaigns.show', $campaign) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $campaign->page_name }}</h6>
                                <small>{{ $campaign->start_date->format('Y-m-d') }}</small>
                            </div>
                            <p class="mb-1">{{ $campaign->platform }} - {{ $campaign->status }}</p>
                            <small class="text-muted">{{ number_format($campaign->budget_total ?? 0, 2) }} ر.س</small>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            <p class="mb-0">لا توجد حملات إعلانية حديثة</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('meym.campaigns.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض الكل
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Car Imports -->
        <div class="col-md-4 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 1.1s;">
                <div class="card-header">
                    <i class="fas fa-car ms-2"></i>
                    أحدث استيرادات السيارات
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentCarImports as $import)
                        <a href="{{ route('carimport.imports.show', $import) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $import->make }} {{ $import->model }}</h6>
                                <small>{{ $import->auction_invoice_date ? $import->auction_invoice_date->format('Y-m-d') : 'N/A' }}</small>
                            </div>
                            <p class="mb-1">{{ $import->vin }} - {{ $import->status }}</p>
                            <small class="text-muted">{{ number_format($import->total_cost, 2) }} ر.س</small>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            <p class="mb-0">لا توجد استيرادات سيارات حديثة</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('carimport.imports.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض الكل
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديد الفترة الزمنية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" name="start_date" value="{{ request('start_date', now()->subMonths(3)->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تطبيق</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if ApexCharts is loaded
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded!');
        return;
    }

    // Mohammed PhoneTech Brand Colors
    const brandColors = {
        primary: '#2c3e50',
        secondary: '#34495e', 
        gold: '#f1c40f',
        silver: '#bdc3c7',
        success: '#27ae60',
        warning: '#f39c12',
        danger: '#e74c3c',
        info: '#3498db'
    };

    try {
        // Sales Chart
        const salesOptions = {
            series: [{
                name: 'المبيعات (ر.س)',
                data: @json($salesByMonth['totals'])
            }],
            chart: {
                height: 350,
                type: 'area',
                fontFamily: 'Cairo, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: [brandColors.gold],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: @json($salesByMonth['months']),
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('ar-SA', {
                            style: 'currency',
                            currency: 'SAR'
                        }).format(value);
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('ar-SA', {
                            style: 'currency',
                            currency: 'SAR'
                        }).format(value);
                    }
                }
            },
            grid: {
                borderColor: brandColors.silver,
                strokeDashArray: 5
            }
        };

        const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
        salesChart.render();

        // Installment Status Chart
        const installmentOptions = {
            series: @json($installmentStatus['data']),
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Cairo, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            labels: @json($installmentStatus['labels']),
            colors: [brandColors.success, brandColors.warning, brandColors.danger],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'الإجمالي',
                                fontSize: '16px',
                                fontWeight: 600,
                                color: brandColors.primary,
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '14px',
                fontFamily: 'Cairo, sans-serif',
                labels: {
                    colors: brandColors.primary
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value, { series, seriesIndex }) {
                        const total = series.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${value} (${percentage}%)`;
                    }
                }
            }
        };

        const installmentChart = new ApexCharts(document.querySelector("#installmentChart"), installmentOptions);
        installmentChart.render();

        // Campaign Performance Chart
        const campaignOptions = {
            series: [
                {
                    name: 'العملاء المحتملين',
                    data: @json($campaignPerformance['leads'])
                },
                {
                    name: 'التحويلات',
                    data: @json($campaignPerformance['conversions'])
                }
            ],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Cairo, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: [brandColors.gold, brandColors.primary],
            xaxis: {
                categories: @json($campaignPerformance['labels']),
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            legend: {
                position: 'top',
                fontSize: '14px',
                fontFamily: 'Cairo, sans-serif',
                labels: {
                    colors: brandColors.primary
                }
            },
            tooltip: {
                theme: 'light'
            },
            grid: {
                borderColor: brandColors.silver,
                strokeDashArray: 5
            }
        };

        const campaignChart = new ApexCharts(document.querySelector("#campaignChart"), campaignOptions);
        campaignChart.render();

        // Inventory Chart
        const inventoryOptions = {
            series: [{
                name: 'الكمية المتوفرة',
                data: @json($productInventory['quantities'])
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Cairo, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: true,
                }
            },
            colors: [brandColors.silver],
            xaxis: {
                categories: @json($productInventory['labels']),
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif',
                        colors: brandColors.primary
                    }
                }
            },
            tooltip: {
                theme: 'light'
            },
            grid: {
                borderColor: brandColors.silver,
                strokeDashArray: 5
            }
        };

        const inventoryChart = new ApexCharts(document.querySelector("#inventoryChart"), inventoryOptions);
        inventoryChart.render();

        console.log('All charts rendered successfully with Mohammed PhoneTech brand colors!');
    } catch (error) {
        console.error('Error rendering charts:', error);
    }

    // Export functions
    document.getElementById('exportPDF').addEventListener('click', function() {
        window.location.href = '{{ route("reports.export-pdf") }}';
    });
    
    document.getElementById('exportExcel').addEventListener('click', function() {
        window.location.href = '{{ route("reports.export-excel") }}';
    });
});
</script>
@endpush
