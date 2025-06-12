@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        إدارة التقسيط
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.installments.overdue') }}" class="btn btn-warning me-2">
            <i class="fas fa-exclamation-triangle me-1"></i>
            الأقساط المتأخرة
        </a>
        <a href="{{ route('phonetech.sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للمبيعات
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $sales->total() }}</div>
            <div class="stats-label">إجمالي فواتير التقسيط</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ number_format($sales->sum('total_amount'), 0) }}</div>
            <div class="stats-label">إجمالي المبيعات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ number_format($sales->sum('remaining_amount'), 0) }}</div>
            <div class="stats-label">المبالغ المتبقية (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $sales->where('is_overdue', true)->count() }}</div>
            <div class="stats-label">الأقساط المتأخرة</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phonetech.installments.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="رقم الفاتورة أو اسم العميل">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>نشط</option>
                        <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>متأخر</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">المتأخرات فقط</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }}>
                        <label class="form-check-label">عرض المتأخرات فقط</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('phonetech.installments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Installments Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>المبلغ الإجمالي</th>
                        <th>المبلغ المتبقي</th>
                        <th>القسط الشهري</th>
                        <th>الأقساط المدفوعة</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="{{ $sale->is_overdue ? 'table-warning' : '' }}">
                        <td><strong>{{ $sale->invoice_id }}</strong></td>
                        <td>
                            {{ $sale->customer_name }}
                            <small class="d-block text-muted">{{ $sale->customer_phone }}</small>
                        </td>
                        <td>{{ $sale->product->name ?? 'منتج محذوف' }}</td>
                        <td>{{ number_format($sale->total_amount, 2) }} دينار</td>
                        <td class="{{ $sale->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($sale->remaining_amount, 2) }} دينار
                        </td>
                        <td>{{ number_format($sale->monthly_installment, 2) }} دينار</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $sale->is_overdue ? 'bg-warning' : 'bg-success' }}" role="progressbar" 
                                     style="width: {{ ($sale->installments_paid / $sale->installment_period_months) * 100 }}%">
                                    {{ $sale->installments_paid }}/{{ $sale->installment_period_months }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @switch($sale->status)
                                @case('Pending')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                @case('Active')
                                    <span class="badge bg-primary">نشط</span>
                                    @break
                                @case('Overdue')
                                    <span class="badge bg-danger">متأخر</span>
                                    @break
                                @case('Completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                            @endswitch
                            @if($sale->is_overdue)
                                <span class="badge bg-danger ms-1">متأخر</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('phonetech.installments.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('phonetech.installments.schedule', $sale) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-calendar"></i>
                                </a>
                                @if($sale->is_overdue)
                                <a href="{{ route('phonetech.installments.send-reminder', $sale) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-bell"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                            <p>لا توجد فواتير تقسيط مطابقة للبحث</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $sales->links() }}
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
                        <a href="{{ route('phonetech.installments.overdue') }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            الأقساط المتأخرة
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.sales') }}?payment_type=installment" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقرير التقسيط
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير إلى Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#sendBulkRemindersModal">
                            <i class="fas fa-bell me-1"></i>
                            إرسال تذكيرات جماعية
                        </button>
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
                <h5 class="modal-title">إرسال تذكيرات جماعية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.installments.send-bulk-reminders') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">نوع التذكير</label>
                        <select class="form-select" name="reminder_type" required>
                            <option value="all_overdue">جميع المتأخرات</option>
                            <option value="due_today">المستحقة اليوم</option>
                            <option value="due_this_week">المستحقة هذا الأسبوع</option>
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
                        <textarea class="form-control" name="message" rows="3" required>تذكير: لديك قسط مستحق الدفع. يرجى مراجعة محمد فون تك في أقرب وقت ممكن.</textarea>
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
    window.location.href = '{{ route("phonetech.installments.export") }}?' + new URLSearchParams(window.location.search);
}
</script>
@endpush
@endsection
