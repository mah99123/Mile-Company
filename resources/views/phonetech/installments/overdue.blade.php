@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-exclamation-triangle me-2"></i>
        الأقساط المتأخرة
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#sendBulkRemindersModal">
            <i class="fas fa-bell me-1"></i>
            إرسال تذكيرات جماعية
        </button>
        <a href="{{ route('phonetech.installments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للتقسيط
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
            <div class="stats-number">{{ $overdueInstallments->total() }}</div>
            <div class="stats-label">إجمالي الأقساط المتأخرة</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ number_format($over30Days = $overdueInstallments->filter(function($sale) { return $sale->sale_date->diffInDays(now()) > 30; })->count()) }}</div>
            <div class="stats-label">متأخر أكثر من 30 يوم</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ number_format($overdueInstallments->sum('remaining_amount'), 0) }}</div>
            <div class="stats-label">المبالغ المتأخرة (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($overdueInstallments->sum('monthly_installment'), 0) }}</div>
            <div class="stats-label">إجمالي الأقساط الشهرية (دينار)</div>
        </div>
    </div>
</div>

<!-- Overdue Installments Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>تاريخ آخر دفعة</th>
                        <th>المبلغ المتأخر</th>
                        <th>مدة التأخير</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueInstallments as $sale)
                    <tr>
                        <td><strong>{{ $sale->invoice_id }}</strong></td>
                        <td>
                            {{ $sale->customer_name }}
                            <small class="d-block text-muted">{{ $sale->customer_phone }}</small>
                        </td>
                        <td>{{ $sale->product->name ?? 'منتج محذوف' }}</td>
                        <td>
                            @php
                                $lastPayment = $sale->installmentPayments->sortByDesc('payment_date')->first();
                                $lastPaymentDate = $lastPayment ? $lastPayment->payment_date : $sale->sale_date;
                            @endphp
                            {{ $lastPaymentDate->format('Y-m-d') }}
                            <small class="d-block text-muted">
                                قبل {{ now()->diffInDays($lastPaymentDate) }} يوم
                            </small>
                        </td>
                        <td class="text-danger">
                            {{ number_format($sale->monthly_installment, 2) }} دينار
                        </td>
                        <td>
                            @php
                                $overdueDays = now()->diffInDays($lastPaymentDate->addMonth());
                                $overdueClass = $overdueDays > 30 ? 'danger' : ($overdueDays > 15 ? 'warning' : 'info');
                            @endphp
                            <span class="badge bg-{{ $overdueClass }}">{{ $overdueDays }} يوم</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('phonetech.installments.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal{{ $sale->id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <a href="{{ route('phonetech.installments.send-reminder', $sale) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-bell"></i>
                                </a>
                            </div>
                            
                            <!-- Add Payment Modal for this sale -->
                            <div class="modal fade" id="addPaymentModal{{ $sale->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">تسجيل دفعة جديدة - {{ $sale->invoice_id }}</h5>
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
                                                    <input type="number" step="0.01" class="form-control" name="amount" value="{{ $sale->monthly_installment }}" required>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>لا توجد أقساط متأخرة حالياً</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $overdueInstallments->links() }}
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#sendBulkRemindersModal">
                            <i class="fas fa-bell me-1"></i>
                            إرسال تذكيرات جماعية
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.sales') }}?payment_type=installment&overdue=1" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقرير المتأخرات
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير إلى Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('phonetech.installments.index') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            جميع التقسيطات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Reminders Modal -->
<div class="modal fade" id="sendBulkRemindersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إرسال تذكيرات جماعية للمتأخرات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.installments.send-bulk-reminders') }}">
                @csrf
                <input type="hidden" name="overdue_only" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">فترة التأخير</label>
                        <select class="form-select" name="overdue_period" required>
                            <option value="all">جميع المتأخرات</option>
                            <option value="more_than_7">أكثر من 7 أيام</option>
                            <option value="more_than_15">أكثر من 15 يوم</option>
                            <option value="more_than_30">أكثر من 30 يوم</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">طريقة الإرسال</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="send_sms" id="send_sms" checked>
                            <label class="form-check-label" for="send_sms">
                                رسالة نصية SMS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="send_whatsapp" id="send_whatsapp" checked>
                            <label class="form-check-label" for="send_whatsapp">
                                واتساب
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نص الرسالة</label>
                        <textarea class="form-control" name="message" rows="3" required>تذكير هام: لديك قسط متأخر عن موعد السداد. يرجى مراجعة محمد فون تك في أقرب وقت ممكن لتجنب أي إجراءات قانونية.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إرسال التذكيرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportToExcel() {
    window.location.href = '{{ route("phonetech.installments.export-overdue") }}';
}
</script>
@endpush
@endsection
