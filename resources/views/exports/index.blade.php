@extends('layouts.app')

@section('content')
<div class="container-fluid rtl">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">تصدير البيانات</h1>
        </div>
    </div>

    <div class="row">
        <!-- تصدير المبيعات -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير المبيعات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.sales.excel') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="sales_start_date">تاريخ البداية</label>
                            <input type="date" class="form-control" id="sales_start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="sales_end_date">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="sales_end_date" name="end_date">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-excel mr-1"></i> تصدير إلى Excel
                            </button>
                            <button type="submit" class="btn btn-danger" formaction="{{ route('exports.sales.pdf') }}">
                                <i class="fas fa-file-pdf mr-1"></i> تصدير إلى PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- تصدير المنتجات -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير المنتجات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.products.excel') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <p>سيتم تصدير جميع المنتجات المتوفرة في النظام.</p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-excel mr-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تصدير الحملات -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير الحملات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.campaigns.excel') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="campaigns_start_date">تاريخ البداية</label>
                            <input type="date" class="form-control" id="campaigns_start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="campaigns_end_date">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="campaigns_end_date" name="end_date">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-excel mr-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- تصدير استيراد السيارات -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير استيراد السيارات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.car-imports.excel') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="car_imports_start_date">تاريخ البداية</label>
                            <input type="date" class="form-control" id="car_imports_start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="car_imports_end_date">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="car_imports_end_date" name="end_date">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-excel mr-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تصدير الأقساط -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير الأقساط</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.installments.excel') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="installment_status">الحالة</label>
                            <select class="form-control" id="installment_status" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="paid">مدفوعة</option>
                                <option value="pending">قيد الانتظار</option>
                                <option value="overdue">متأخرة</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-excel mr-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- تصدير تقرير الأرباح والخسائر -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تصدير تقرير الأرباح والخسائر</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exports.profit-loss.pdf') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="profit_loss_year">السنة</label>
                            <select class="form-control" id="profit_loss_year" name="year" required>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="profit_loss_month">الشهر (اختياري)</label>
                            <select class="form-control" id="profit_loss_month" name="month">
                                <option value="">جميع الأشهر</option>
                                <option value="1">يناير</option>
                                <option value="2">فبراير</option>
                                <option value="3">مارس</option>
                                <option value="4">أبريل</option>
                                <option value="5">مايو</option>
                                <option value="6">يونيو</option>
                                <option value="7">يوليو</option>
                                <option value="8">أغسطس</option>
                                <option value="9">سبتمبر</option>
                                <option value="10">أكتوبر</option>
                                <option value="11">نوفمبر</option>
                                <option value="12">ديسمبر</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-file-pdf mr-1"></i> تصدير إلى PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
