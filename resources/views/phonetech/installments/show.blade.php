@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        تفاصيل التقسيط: {{ $sale->invoice_id }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.installments.schedule', $sale) }}" class="btn btn-info me-2">
            <i class="fas fa-calendar me-1"></i>
            جدول الأقساط
        </a>
        <a href="{{ route('phonetech.installments.print-schedule', $sale) }}" class="btn btn-success me-2" target="_blank">
            <i class="fas fa-print me-1"></i>
            طباعة الجدول
        </a>
        <a href="{{ route('phonetech.installments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Sale Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل الفاتورة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>رقم الفاتورة:</strong></td>
                                <td><code>{{ $sale->invoice_id }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ البيع:</strong></td>
                                <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td><strong>المنتج:</strong></td>
                                <td>{{ $sale->product->name ?? 'منتج محذوف' }}</td>
                            </tr>
                            <tr>
                                <td><strong>الكمية:</strong></td>
                                <td>{{ $sale->quantity }}</td>
                            </tr>
                            <tr>
                                <td><strong>سعر الوحدة:</strong></td>
                                <td>{{ number_format($sale->unit_price, 2) }} دينار</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>اسم العميل:</strong></td>
                                <td>{{ $sale->customer_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>رقم الهاتف:</strong></td>
                                <td>{{ $sale->customer_phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>العنوان:</strong></td>
                                <td>{{ $sale->customer_address ?: 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong>البريد الإلكتروني:</strong></td>
                                <td>{{ $sale->customer_email ?: 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong>رقم الهوية:</strong></td>
                                <td>{{ $sale->customer_id_number ?: 'غير محدد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل التقسيط</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>المبلغ الإجمالي:</strong></td>
                                <td class="text-primary">{{ number_format($sale->total_amount, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>الدفعة الأولى:</strong></td>
                                <td>{{ number_format($sale->down_payment, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>المبلغ المتبقي:</strong></td>
                                <td class="{{ $sale->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($sale->remaining_amount, 2) }} دينار
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>فترة التقسيط:</strong></td>
                                <td>{{ $sale->installment_period_months }} شهر</td>
                            </tr>
                            <tr>
                                <td><strong>القسط الشهري:</strong></td>
                                <td>{{ number_format($sale->monthly_installment, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>الأقساط المدفوعة:</strong></td>
                                <td>{{ $sale->installments_paid }}/{{ $sale->installment_period_months }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $sale->is_overdue ? 'bg-warning' : 'bg-success' }}" role="progressbar" 
                                 style="width: {{ ($sale->installments_paid / $sale->installment_period_months) * 100 }}%">
                                {{ round(($sale->installments_paid / $sale->installment_period_months) * 100) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">سجل المدفوعات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>تاريخ الدفع</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sale->down_payment > 0)
                            <tr>
                                <td>-</td>
                                <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($sale->down_payment, 2) }} دينار</td>
                                <td><span class="badge bg-info">دفعة أولى</span></td>
                                <td>-</td>
                            </tr>
                            @endif
                            
                            @forelse($sale->installmentPayments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->payment_date }}</td>
                                <td>{{ number_format($payment->amount, 2) }} دينار</td>
                                <td>
                                    @switch($payment->payment_method)
                                        @case('cash')
                                            <span class="badge bg-success">نقدي</span>
                                            @break
                                        @case('bank_transfer')
                                            <span class="badge bg-primary">تحويل بنكي</span>
                                            @break
                                        @case('check')
                                            <span class="badge bg-secondary">شيك</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $payment->notes ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    لا توجد مدفوعات مسجلة بعد
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="2"><strong>الإجمالي المدفوع</strong></td>
                                <td colspan="3">
                                    <strong>{{ number_format($sale->total_amount - $sale->remaining_amount, 2) }} دينار</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">حالة التقسيط</h5>
            </div>
            <div class="card-body text-center">
                @switch($sale->status)
                    @case('Pending')
                        <span class="badge bg-warning fs-6 p-3">في الانتظار</span>
                        @break
                    @case('Active')
                        <span class="badge bg-primary fs-6 p-3">نشط</span>
                        @break
                    @case('Overdue')
                        <span class="badge bg-danger fs-6 p-3">متأخر</span>
                        @break
                    @case('Completed')
                        <span class="badge bg-success fs-6 p-3">مكتمل</span>
                        @break
                @endswitch
                
                @if($sale->is_overdue)
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    هذا التقسيط متأخر عن موعد السداد
                </div>
                @endif
            </div>
        </div>

        <!-- Next Payment -->
        @if($sale->status != 'Completed')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">الدفعة القادمة</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="text-primary">{{ number_format($nextPaymentAmount, 2) }} دينار</h3>
                    <p class="text-muted">
                        تاريخ الاستحقاق: {{ $nextPaymentDate ? $nextPaymentDate->format('Y-m-d') : 'غير محدد' }}
                    </p>
                    
                    @if($nextPaymentDate && $nextPaymentDate->isPast())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        هذه الدفعة متأخرة عن موعدها
                    </div>
                    @elseif($nextPaymentDate && $nextPaymentDate->diffInDays(now()) <= 7)
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-1"></i>
                        موعد الدفعة قريب ({{ $nextPaymentDate->diffInDays(now()) }} أيام متبقية)
                    </div>
                    @endif
                </div>
                
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="fas fa-plus me-1"></i>
                    تسجيل دفعة جديدة
                </button>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('phonetech.installments.schedule', $sale) }}" class="btn btn-info">
                        <i class="fas fa-calendar me-1"></i>
                        جدول الأقساط
                    </a>
                    <a href="{{ route('phonetech.installments.print-schedule', $sale) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-print me-1"></i>
                        طباعة الجدول
                    </a>
                    @if($sale->status != 'Completed')
                    <a href="{{ route('phonetech.installments.send-reminder', $sale) }}" class="btn btn-warning">
                        <i class="fas fa-bell me-1"></i>
                        إرسال تذكير
                    </a>
                    @endif
                    <a href="{{ route('phonetech.sales.show', $sale) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-file-invoice me-1"></i>
                        عرض الفاتورة
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الاتصال</h5>
            </div>
            <div class="card-body">
                <h6>{{ $sale->customer_name }}</h6>
                <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $sale->customer_phone }}</p>
                @if($sale->customer_email)
                <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $sale->customer_email }}</p>
                @endif
                @if($sale->customer_address)
                <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $sale->customer_address }}</p>
                @endif
                
                <div class="mt-3">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sale->customer_phone) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fab fa-whatsapp me-1"></i>
                        واتساب
                    </a>
                    <a href="tel:{{ $sale->customer_phone }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-phone me-1"></i>
                        اتصال
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تسجيل دفعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.installments.add-payment', $sale) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount" value="{{ $nextPaymentAmount }}" required>
                        <small class="text-muted">المبلغ المتبقي: {{ number_format($sale->remaining_amount, 2) }} دينار</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash">نقدي</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="check">شيك</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تسجيل الدفعة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
