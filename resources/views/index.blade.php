@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar me-2"></i>
        التقارير والإحصائيات
    </h1>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ number_format(150000) }}</div>
            <div class="stats-label">إجمالي الإيرادات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format(45000) }}</div>
            <div class="stats-label">صافي الربح (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">125</div>
            <div class="stats-label">إجمالي المعاملات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">15</div>
            <div class="stats-label">العملاء النشطين</div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row">
    @can('access meym')
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bullhorn me-2"></i>
                    تقارير منصة ميم
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">تقارير شاملة عن الحملات الإعلانية والميزانيات والأداء</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.campaigns') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line me-1"></i>
                        تقرير الحملات
                    </a>
                    <a href="{{ route('reports.campaigns') }}?export_pdf=1" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-1"></i>
                        تصدير PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('access phonetech')
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-mobile-alt me-2"></i>
                    تقارير محمد فون تك
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">تقارير المبيعات والمخزون والأقساط والموردين</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.sales') }}" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i>
                        تقرير المبيعات
                    </a>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-outline-success">
                        <i class="fas fa-boxes me-1"></i>
                        تقرير المخزون
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('access carimport')
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-car me-2"></i>
                    تقارير استيراد السيارات
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">تقارير عمليات الاستيراد والشحن والأرباح</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.car-imports') }}" class="btn btn-info">
                        <i class="fas fa-ship me-1"></i>
                        تقرير الاستيراد
                    </a>
                    <a href="{{ route('reports.car-imports') }}?export_pdf=1" class="btn btn-outline-info">
                        <i class="fas fa-file-pdf me-1"></i>
                        تصدير PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('view financial reports')
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    التقارير المالية
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">تقارير الأرباح والخسائر والميزانية العمومية</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.profit-loss') }}" class="btn btn-warning">
                        <i class="fas fa-chart-pie me-1"></i>
                        الأرباح والخسائر
                    </a>
                    <a href="{{ route('reports.profit-loss') }}?export_pdf=1" class="btn btn-outline-warning">
                        <i class="fas fa-file-pdf me-1"></i>
                        تصدير PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    تقارير العملاء
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">تقارير العملاء والمدفوعات والمتأخرات</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.customers') }}" class="btn btn-secondary">
                        <i class="fas fa-user-friends me-1"></i>
                        تقرير العملاء
                    </a>
                    <a href="{{ route('reports.overdue') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        المتأخرات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>
                    تقارير مخصصة
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">إنشاء تقارير مخصصة حسب الحاجة</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#customReportModal">
                        <i class="fas fa-plus me-1"></i>
                        تقرير مخصص
                    </button>
                    <a href="{{ route('reports.export-all') }}" class="btn btn-outline-dark">
                        <i class="fas fa-download me-1"></i>
                        تصدير الكل
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reports -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">التقارير الأخيرة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>نوع التقرير</th>
                        <th>الفترة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>تقرير المبيعات الشهري</td>
                        <td>ديسمبر 2023</td>
                        <td>2023-12-31</td>
                        <td><span class="badge bg-success">مكتمل</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>تقرير الحملات الإعلانية</td>
                        <td>نوفمبر 2023</td>
                        <td>2023-11-30</td>
                        <td><span class="badge bg-success">مكتمل</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>تقرير استيراد السيارات</td>
                        <td>أكتوبر 2023</td>
                        <td>2023-10-31</td>
                        <td><span class="badge bg-warning">قيد المعالجة</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="fas fa-clock"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Report Modal -->
<div class="modal fade" id="customReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء تقرير مخصص</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('reports.custom') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">نوع التقرير</label>
                                <select class="form-select" name="report_type" required>
                                    <option value="">اختر نوع التقرير</option>
                                    <option value="sales">المبيعات</option>
                                    <option value="campaigns">الحملات</option>
                                    <option value="imports">الاستيراد</option>
                                    <option value="financial">مالي</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تنسيق التصدير</label>
                                <select class="form-select" name="export_format" required>
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" name="date_from" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" name="date_to" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحقول المطلوبة</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="customer_info" checked>
                                    <label class="form-check-label">معلومات العميل</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="amounts" checked>
                                    <label class="form-check-label">المبالغ</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="dates" checked>
                                    <label class="form-check-label">التواريخ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إنشاء التقرير</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
