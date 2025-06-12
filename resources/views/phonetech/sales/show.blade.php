@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-receipt me-2"></i>
        فاتورة رقم: {{ $sale->invoice_id }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="fas fa-plus me-1"></i>
            إضافة دفعة
        </button>
        <button type="button" class="btn btn-primary me-2" onclick="printInvoice()">
            <i class="fas fa-print me-1"></i>
            طباعة الفاتورة
        </button>
        <a href="{{ route('phonetech.sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Invoice Details -->
        <div class="card mb-4" id="invoice-content">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل الفاتورة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>رقم الفاتورة:</strong></td>
                                <td>{{ $sale->invoice_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>اسم العميل:</strong></td>
                                <td>{{ $sale->customer_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>رقم الهاتف:</strong></td>
                                <td>{{ $sale->customer_phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>المنتج:</strong></td>
                                <td>{{ $sale->product->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ البيع:</strong></td>
                                <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>المبلغ الإجمالي:</strong></td>
                                <td>{{ number_format($sale->total_amount, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>الدفعة المقدمة:</strong></td>
                                <td>{{ number_format($sale->down_payment, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>المبلغ المتبقي:</strong></td>
                                <td class="text-danger">{{ number_format($sale->remaining_amount, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>قيمة القسط:</strong></td>
                                <td>{{ number_format($sale->installment_amount, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>عدد الأقساط:</strong></td>
                                <td>{{ $sale->installment_period_months }} شهر</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ ($sale->installments_paid / $sale->installment_period_months) * 100 }}%">
                                {{ round(($sale->installments_paid / $sale->installment_period_months) * 100, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            تم دفع {{ $sale->installments_paid }} من أصل {{ $sale->installment_period_months }} قسط
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">تاريخ المدفوعات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الإيصال</th>
                                <th>تاريخ الدفع</th>
                                <th>المبلغ المدفوع</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->installmentPayments as $payment)
                            <tr>
                                <td><code>{{ $payment->receipt_number }}</code></td>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($payment->amount_paid, 2) }} دينار</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">لا توجد مدفوعات مسجلة</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">حالة الفاتورة</h5>
            </div>
            <div class="card-body text-center">
                @switch($sale->status)
                    @case('Pending')
                        <span class="badge bg-warning fs-6 p-3">في الانتظار</span>
                        @break
                    @case('Active')
                        <span class="badge bg-primary fs-6 p-3">نشط</span>
                        @break
                    @case('Completed')
                        <span class="badge bg-success fs-6 p-3">مكتمل</span>
                        @break
                    @case('Overdue')
                        <span class="badge bg-danger fs-6 p-3">متأخر</span>
                        @break
                @endswitch
                
                @if($sale->status == 'Active' && $sale->remaining_amount > 0)
                <div class="mt-3">
                    <p class="mb-1"><strong>القسط التالي:</strong></p>
                    <p class="text-danger">{{ $sale->next_installment_due_date->format('Y-m-d') }}</p>
                    @if($sale->is_overdue)
                        <small class="text-danger">متأخر عن الموعد!</small>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($sale->remaining_amount > 0)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="fas fa-plus me-1"></i>
                        إضافة دفعة
                    </button>
                    @endif
                    <button type="button" class="btn btn-primary" onclick="sendWhatsAppReminder()">
                        <i class="fab fa-whatsapp me-1"></i>
                        إرسال تذكير واتساب
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="printInvoice()">
                        <i class="fas fa-print me-1"></i>
                        طباعة الفاتورة
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات العميل</h5>
            </div>
            <div class="card-body">
                <h6>{{ $sale->customer_name }}</h6>
                <p class="mb-2"><i class="fas fa-phone me-2"></i>{{ $sale->customer_phone }}</p>
                <p class="mb-0"><i class="fas fa-calendar me-2"></i>عميل منذ: {{ $sale->sale_date->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة دفعة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.sales.add-payment', $sale) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">المبلغ المتبقي</label>
                        <input type="text" class="form-control" value="{{ number_format($sale->remaining_amount, 2) }} دينار" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المبلغ المدفوع <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount_paid" 
                               max="{{ $sale->remaining_amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الإيصال <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="receipt_number" 
                               value="REC-{{ $sale->invoice_id }}-{{ time() }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إضافة الدفعة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printInvoice() {
    window.print();
}

function sendWhatsAppReminder() {
    const phone = '{{ $sale->customer_phone }}';
    const message = `تذكير: لديك قسط مستحق بتاريخ {{ $sale->next_installment_due_date->format('Y-m-d') }} بمبلغ {{ number_format($sale->installment_amount, 2) }} دينار للمنتج {{ $sale->product->name }}`;
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
</script>
@endpush
@endsection
