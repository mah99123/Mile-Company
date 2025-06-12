@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar me-2"></i>
        جدول الأقساط: {{ $sale->invoice_id }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.installments.print-schedule', $sale) }}" class="btn btn-success me-2" target="_blank">
            <i class="fas fa-print me-1"></i>
            طباعة الجدول
        </a>
        <a href="{{ route('phonetech.installments.show', $sale) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتفاصيل
        </a>
    </div>
</div>

<!-- Customer Info -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>معلومات العميل</h5>
                <p class="mb-1"><strong>الاسم:</strong> {{ $sale->customer_name }}</p>
                <p class="mb-1"><strong>الهاتف:</strong> {{ $sale->customer_phone }}</p>
                @if($sale->customer_address)
                <p class="mb-0"><strong>العنوان:</strong> {{ $sale->customer_address }}</p>
                @endif
            </div>
            <div class="col-md-6">
                <h5>معلومات الفاتورة</h5>
                <p class="mb-1"><strong>رقم الفاتورة:</strong> {{ $sale->invoice_id }}</p>
                <p class="mb-1"><strong>تاريخ البيع:</strong> {{ $sale->sale_date->format('Y-m-d') }}</p>
                <p class="mb-0"><strong>المنتج:</strong> {{ $sale->product->name ?? 'منتج محذوف' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Payment Summary -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <h4 class="text-primary">{{ number_format($sale->total_amount, 2) }}</h4>
                <small class="text-muted">المبلغ الإجمالي (دينار)</small>
            </div>
            <div class="col-md-3 text-center">
                <h4 class="text-success">{{ number_format($sale->down_payment, 2) }}</h4>
                <small class="text-muted">الدفعة الأولى (دينار)</small>
            </div>
            <div class="col-md-3 text-center">
                <h4 class="text-info">{{ $sale->installment_period_months }}</h4>
                <small class="text-muted">فترة التقسيط (شهر)</small>
            </div>
            <div class="col-md-3 text-center">
                <h4 class="text-warning">{{ number_format($sale->monthly_installment, 2) }}</h4>
                <small class="text-muted">القسط الشهري (دينار)</small>
            </div>
        </div>
    </div>
</div>

<!-- Payment Schedule -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">جدول الأقساط</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم القسط</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>تاريخ الدفع</th>
                        <th>المبلغ المدفوع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedule as $installment)
                    <tr class="
                        @switch($installment['status'])
                            @case('Paid') table-success @break
                            @case('Overdue') table-danger @break
                            @default @break
                        @endswitch
                    ">
                        <td>{{ $installment['installment_number'] }}</td>
                        <td>{{ $installment['due_date'] }}</td>
                        <td>{{ number_format($installment['amount'], 2) }} دينار</td>
                        <td>
                            @switch($installment['status'])
                                @case('Paid')
                                    <span class="badge bg-success">مدفوع</span>
                                    @break
                                @case('Overdue')
                                    <span class="badge bg-danger">متأخر</span>
                                    @break
                                @default
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $installment['payment_date'] ?: '-' }}</td>
                        <td>{{ $installment['payment_amount'] ? number_format($installment['payment_amount'], 2) . ' دينار' : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="2"><strong>الإجمالي</strong></td>
                        <td><strong>{{ number_format($sale->monthly_installment * $sale->installment_period_months, 2) }} دينار</strong></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Payment Statistics -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات السداد</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>نسبة السداد</h6>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar {{ $sale->is_overdue ? 'bg-warning' : 'bg-success' }}" role="progressbar" 
                                 style="width: {{ ($sale->installments_paid / $sale->installment_period_months) * 100 }}%">
                                {{ round(($sale->installments_paid / $sale->installment_period_months) * 100) }}%
                            </div>
                        </div>
                        <p>
                            <strong>الأقساط المدفوعة:</strong> {{ $sale->installments_paid }} من {{ $sale->installment_period_months }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>المبالغ</h6>
                        <p class="mb-1">
                            <strong>المبلغ المدفوع:</strong> 
                            {{ number_format($sale->total_amount - $sale->remaining_amount, 2) }} دينار
                            ({{ round((($sale->total_amount - $sale->remaining_amount) / $sale->total_amount) * 100) }}%)
                        </p>
                        <p class="mb-1">
                            <strong>المبلغ المتبقي:</strong> 
                            {{ number_format($sale->remaining_amount, 2) }} دينار
                            ({{ round(($sale->remaining_amount / $sale->total_amount) * 100) }}%)
                        </p>
                        <p class="mb-0">
                            <strong>الأقساط المتبقية:</strong>
                            {{ $sale->installment_period_months - $sale->installments_paid }} قسط
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
