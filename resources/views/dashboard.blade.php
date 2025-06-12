@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 animate__animated animate__fadeInRight">
                        <i class="fas fa-tachometer-alt text-warning ms-2"></i>
                        لوحة التحكم الرئيسية - شركة الميل
                    </h1>
                    <p class="text-muted mb-0">مرحباً بك {{ Auth::user()->name }}، إليك نظرة عامة على النظام</p>
                </div>
                <div class="text-muted">
                    <i class="fas fa-calendar-alt ms-1"></i>
                    {{ now()->format('Y-m-d') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="stats-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-number">{{ number_format($totalSales) }}</div>
                <div class="stats-label">إجمالي المبيعات</div>
                <div class="mt-3">
                    <a href="{{ route('phonetech.sales.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card success animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="stats-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stats-number">{{ number_format($totalProducts) }}</div>
                <div class="stats-label">إجمالي المنتجات</div>
                <div class="mt-3">
                    <a href="{{ route('phonetech.products.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right ms-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card gold animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="stats-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stats-number">{{ number_format($totalCampaigns) }}</div>
                <div class="stats-label">إجمالي الحملات</div>
                <div class="mt-3">
                    <a href="{{ route('meym.campaigns.index') }}" class="btn btn-light btn-sm">
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
                <div class="stats-number">{{ number_format($totalCarImports) }}</div>
                <div class="stats-label">استيراد السيارات</div>
                <div class="mt-3">
                    <a href="{{ route('carimport.imports.index') }}" class="btn btn-light btn-sm">
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
                    المبيعات خلال الـ 6 أشهر الماضية
                </div>
                <div class="card-body">
                    <div id="salesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Installments Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <div class="card-header">
                    <i class="fas fa-chart-pie ms-2"></i>
                    حالة الأقساط
                </div>
                <div class="card-body">
                    <div id="installmentChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="row mb-4">
        <!-- Campaign Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                <div class="card-header">
                    <i class="fas fa-chart-doughnut ms-2"></i>
                    حالة الحملات
                </div>
                <div class="card-body">
                    <div id="campaignChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Car Import Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
                <div class="card-header">
                    <i class="fas fa-chart-pie ms-2"></i>
                    حالة استيراد السيارات
                </div>
                <div class="card-body">
                    <div id="carImportChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row mb-4">
        <!-- Latest Sales -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
                <div class="card-header">
                    <i class="fas fa-shopping-cart ms-2"></i>
                    أحدث المبيعات
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>السعر</th>
                                    <th>التاريخ</th>
                                    <th>البائع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestSales as $sale)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning rounded-circle p-2 ms-2">
                                                <i class="fas fa-mobile-alt text-white"></i>
                                            </div>
                                            {{ $sale->product->name ?? 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: linear-gradient(45deg, #f1c40f, #f39c12); color: white;">
                                            {{ number_format($sale->total_amount, 2) }} ر.س
                                        </span>
                                    </td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $sale->creator->name ?? 'غير محدد' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        لا توجد مبيعات حديثة
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('phonetech.sales.index') }}" class="btn btn-gold btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض جميع المبيعات
                    </a>
                </div>
            </div>
        </div>

        <!-- Latest Campaigns -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 1s;">
                <div class="card-header">
                    <i class="fas fa-bullhorn ms-2"></i>
                    أحدث الحملات
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم الصفحة</th>
                                    <th>الحالة</th>
                                    <th>تاريخ البدء</th>
                                    <th>تاريخ الانتهاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestCampaigns as $campaign)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning rounded-circle p-2 ms-2">
                                                <i class="fas fa-bullhorn text-white"></i>
                                            </div>
                                            {{ $campaign->page_name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($campaign->status == 'active')
                                            <span class="badge bg-success">نشطة</span>
                                        @elseif($campaign->status == 'completed')
                                            <span class="badge bg-primary">مكتملة</span>
                                        @else
                                            <span class="badge bg-warning">قيد الانتظار</span>
                                        @endif
                                    </td>
                                    <td>{{ $campaign->start_date }}</td>
                                    <td>{{ $campaign->end_date }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        لا توجد حملات حديثة
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('meym.campaigns.index') }}" class="btn btn-gold btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض جميع الحملات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Row -->
    <div class="row">
        <!-- Overdue Installments Alert -->
        @if($overdueInstallments > 0)
        <div class="col-lg-6 mb-4">
            <div class="card border-danger animate__animated animate__fadeInUp" style="animation-delay: 1.1s;">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-exclamation-triangle ms-2"></i>
                    تنبيه: أقساط متأخرة
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger rounded-circle p-3 ms-3">
                            <i class="fas fa-exclamation-triangle text-white fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="text-danger mb-1">{{ $overdueInstallments }}</h4>
                            <p class="text-muted mb-0">قسط متأخر يحتاج متابعة فورية</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('phonetech.installments.overdue') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-eye ms-1"></i>
                        عرض الأقساط المتأخرة
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Latest Car Imports -->
        <div class="col-lg-6 mb-4">
            <div class="card animate__animated animate__fadeInUp" style="animation-delay: 1.2s;">
                <div class="card-header">
                    <i class="fas fa-car ms-2"></i>
                    أحدث عمليات استيراد السيارات
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم اللوت</th>
                                    <th>الحالة</th>
                                    <th>شركة الشحن</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestCarImports as $carImport)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-circle p-2 ms-2">
                                                <i class="fas fa-car text-white"></i>
                                            </div>
                                            {{ $carImport->lot_number }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($carImport->shipping_status == 'Pending')
                                            <span class="badge bg-warning">قيد الانتظار</span>
                                        @elseif($carImport->shipping_status == 'Shipped')
                                            <span class="badge bg-primary">تم الشحن</span>
                                        @elseif($carImport->shipping_status == 'Arrived')
                                            <span class="badge bg-info">وصلت</span>
                                        @else
                                            <span class="badge bg-success">تم التسليم</span>
                                        @endif
                                    </td>
                                    <td>{{ $carImport->shipping_company }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        لا توجد عمليات استيراد حديثة
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('carimport.imports.index') }}" class="btn btn-gold btn-sm">
                        <i class="fas fa-list ms-1"></i>
                        عرض جميع عمليات الاستيراد
                    </a>
                </div>
            </div>
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
                name: 'المبيعات',
                data: @json(array_column($salesData, 'total'))
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
                categories: @json(array_column($salesData, 'month')),
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

        // Installment Chart
        const installmentOptions = {
            series: [
                {{ $installmentStats['paid'] }}, 
                {{ $installmentStats['pending'] }}, 
                {{ $installmentStats['overdue'] }}
            ],
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
            labels: ['مدفوعة', 'قيد الانتظار', 'متأخرة'],
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

        // Campaign Chart
        const campaignOptions = {
            series: [
                {{ $campaignStats['active'] }}, 
                {{ $campaignStats['completed'] }}, 
                {{ $campaignStats['pending'] }}
            ],
            chart: {
                type: 'pie',
                height: 300,
                fontFamily: 'Cairo, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            labels: ['نشطة', 'مكتملة', 'قيد الانتظار'],
            colors: [brandColors.success, brandColors.primary, brandColors.warning],
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

        const campaignChart = new ApexCharts(document.querySelector("#campaignChart"), campaignOptions);
        campaignChart.render();

        // Car Import Chart
        const carImportOptions = {
            series: [
                {{ $carImportStats['pending'] }}, 
                {{ $carImportStats['shipped'] }}, 
                {{ $carImportStats['arrived'] }}, 
                {{ $carImportStats['delivered'] }}
            ],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Cairo, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            labels: ['قيد الانتظار', 'تم الشحن', 'وصلت', 'تم التسليم'],
            colors: [brandColors.warning, brandColors.primary, brandColors.info, brandColors.success],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%'
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

        const carImportChart = new ApexCharts(document.querySelector("#carImportChart"), carImportOptions);
        carImportChart.render();

        console.log('All charts rendered successfully with Mohammed PhoneTech brand colors!');
    } catch (error) {
        console.error('Error rendering charts:', error);
    }
});
</script>
@endpush
