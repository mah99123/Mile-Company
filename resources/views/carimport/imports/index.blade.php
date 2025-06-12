@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-car me-2"></i>
        إدارة استيراد السيارات
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('carimport.imports.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة عملية استيراد جديدة
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $carImports->total() }}</div>
            <div class="stats-label">إجمالي العمليات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ $carImports->where('shipping_status', 'Pending')->count() }}</div>
            <div class="stats-label">في الانتظار</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $carImports->where('shipping_status', 'Shipped')->count() }}</div>
            <div class="stats-label">قيد الشحن</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $carImports->where('shipping_status', 'Delivered')->count() }}</div>
            <div class="stats-label">تم التسليم</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('carimport.imports.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="رقم اللوت أو الشركة">
                </div>
                <div class="col-md-2">
                    <label class="form-label">حالة الشحن</label>
                    <select class="form-select" name="shipping_status">
                        <option value="">جميع الحالات</option>
                        <option value="Pending" {{ request('shipping_status') == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="Shipped" {{ request('shipping_status') == 'Shipped' ? 'selected' : '' }}>قيد الشحن</option>
                        <option value="Arrived" {{ request('shipping_status') == 'Arrived' ? 'selected' : '' }}>وصل</option>
                        <option value="Delivered" {{ request('shipping_status') == 'Delivered' ? 'selected' : '' }}>تم التسليم</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">شركة المشتري</label>
                    <select class="form-select" name="buyer_company">
                        <option value="">جميع الشركات</option>
                        @foreach($buyerCompanies as $company)
                            <option value="{{ $company }}" {{ request('buyer_company') == $company ? 'selected' : '' }}>
                                {{ $company }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>
                        بحث
                    </button>
                    <a href="{{ route('carimport.imports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i>
                        إعادة تعيين
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Car Imports Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>نوع المزاد</th>
                        <th>رقم اللوت</th>
                        <th>شركة المشتري</th>
                        <th>المبلغ الإجمالي</th>
                        <th>المبلغ المستلم</th>
                        <th>المبلغ المتبقي</th>
                        <th>حالة الشحن</th>
                        <th>الموظف المسؤول</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carImports as $import)
                    <tr>
                        <td>
                            <span class="badge bg-info">{{ $import->auction_type }}</span>
                        </td>
                        <td>
                            <strong>{{ $import->lot_number }}</strong>
                        </td>
                        <td>{{ $import->buyer_company }}</td>
                        <td>{{ number_format($import->total_with_transfer, 2) }} {{ $import->currency }}</td>
                        <td class="text-success">{{ number_format($import->amount_received, 2) }} {{ $import->currency }}</td>
                        <td class="{{ $import->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($import->remaining_amount, 2) }} {{ $import->currency }}
                        </td>
                        <td>
                            @switch($import->shipping_status)
                                @case('Pending')
                                    <span class="badge bg-warning">في الانتظار</span>
                                    @break
                                @case('Shipped')
                                    <span class="badge bg-primary">قيد الشحن</span>
                                    @break
                                @case('Arrived')
                                    <span class="badge bg-info">وصل</span>
                                    @break
                                @case('Delivered')
                                    <span class="badge bg-success">تم التسليم</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $import->employee->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('carimport.imports.show', $import) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('carimport.imports.edit', $import) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('carimport.imports.destroy', $import) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذه العملية؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-car fa-3x mb-3"></i>
                            <p>لا توجد عمليات استيراد مطابقة للبحث</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $carImports->links() }}
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
                        <a href="{{ route('carimport.imports.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>
                            إضافة عملية جديدة
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.car-imports') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقرير الاستيراد
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير إلى Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                            <i class="fas fa-edit me-1"></i>
                            تحديث مجمع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث مجمع لحالة الشحن</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('carimport.imports.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اختر العمليات</label>
                        <select class="form-select" name="import_ids[]" multiple required>
                            @foreach($carImports as $import)
                                <option value="{{ $import->id }}">{{ $import->lot_number }} - {{ $import->buyer_company }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اضغط Ctrl لاختيار عدة عمليات</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة الجديدة</label>
                        <select class="form-select" name="new_status" required>
                            <option value="">اختر الحالة</option>
                            <option value="Pending">في الانتظار</option>
                            <option value="Shipped">قيد الشحن</option>
                            <option value="Arrived">وصل</option>
                            <option value="Delivered">تم التسليم</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportToExcel() {
    window.location.href = '{{ route("carimport.imports.export") }}?' + new URLSearchParams(window.location.search);
}
</script>
@endpush
@endsection
