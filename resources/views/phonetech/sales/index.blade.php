@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-receipt me-2"></i>
        إدارة المبيعات والأقساط - محمد فون تك
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.sales.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إنشاء فاتورة جديدة
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $sales->total() }}</div>
            <div class="stats-label">إجمالي الفواتير</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ $sales->where('status', 'Active')->count() }}</div>
            <div class="stats-label">فواتير نشطة</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $sales->where('status', 'Completed')->count() }}</div>
            <div class="stats-label">فواتير مكتملة</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $sales->where('status', 'Active')->where('next_installment_due_date', '<', now())->count() }}</div>
            <div class="stats-label">أقساط متأخرة</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phonetech.sales.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم العميل أو رقم الهاتف">
                </div>
                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>نشط</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>متأخر</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الأقساط المتأخرة</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }}>
                        <label class="form-check-label">عرض المتأخرة فقط</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary d-block w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sales Table -->
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
                        <th>القسط التالي</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="{{ $sale->is_overdue ? 'table-danger' : '' }}">
                        <td>
                            <strong>{{ $sale->invoice_id }}</strong>
                            @if($sale->is_overdue)
                                <span class="badge bg-danger ms-2">متأخر</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $sale->customer_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $sale->customer_phone }}</small>
                            </div>
                        </td>
                        <td>{{ $sale->product->name }}</td>
                        <td>{{ number_format($sale->total_amount, 2) }} دينار</td>
                        <td class="{{ $sale->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($sale->remaining_amount, 2) }} دينار
                        </td>
                        <td>
                            @if($sale->remaining_amount > 0)
                                <div>
                                    <strong>{{ $sale->next_installment_due_date->format('Y-m-d') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($sale->installment_amount, 2) }} دينار</small>
                                </div>
                            @else
                                <span class="text-success">مكتمل</span>
                            @endif
                        </td>
                        <td>
                            @switch($sale->status)
                                @case('Pending')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                @case('Active')
                                    <span class="badge bg-primary">نشط</span>
                                    @break
                                @case('Completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                                @case('Overdue')
                                    <span class="badge bg-danger">متأخر</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('phonetech.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($sale->remaining_amount > 0)
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="addPayment({{ $sale->invoice_id }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="sendWhatsAppReminder('{{ $sale->customer_phone }}', '{{ $sale->customer_name }}', '{{ $sale->next_installment_due_date->format('Y-m-d') }}', '{{ number_format($sale->installment_amount, 2) }}')">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-receipt fa-3x mb-3"></i>
                            <p>لا توجد فواتير مطابقة للبحث</p>
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
                        <a href="{{ route('phonetech.sales.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>
                            فاتورة جديدة
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.sales') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقرير المبيعات
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير إلى Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning w-100 mb-2" onclick="sendBulkReminders()">
                            <i class="fab fa-whatsapp me-1"></i>
                            تذكيرات جماعية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function addPayment(invoiceId) {
    window.location.href = `/phonetech/sales/${invoiceId}#addPayment`;
}

function sendWhatsAppReminder(phone, customerName, dueDate, amount) {
    const message = `تذكير: عزيزي ${customerName}، لديك قسط مستحق بتاريخ ${dueDate} بمبلغ ${amount} دينار. شكراً لك - محمد فون تك`;
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function exportToExcel() {
    window.location.href = '{{ route("phonetech.sales.export") }}?' + new URLSearchParams(window.location.search);
}

function sendBulkReminders() {
    if (confirm('هل تريد إرسال تذكيرات لجميع العملاء الذين لديهم أقساط مستحقة؟')) {
        // إرسال طلب AJAX لإرسال التذكيرات الجماعية
        fetch('{{ route("phonetech.sales.bulk-reminders") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`تم إرسال ${data.count} تذكير بنجاح`);
            } else {
                alert('حدث خطأ أثناء إرسال التذكيرات');
            }
        });
    }
}
</script>
@endpush
@endsection
