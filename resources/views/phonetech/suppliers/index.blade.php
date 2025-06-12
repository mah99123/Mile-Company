@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-truck me-2"></i>
        إدارة الموردين
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة مورد جديد
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $suppliers->total() }}</div>
            <div class="stats-label">إجمالي الموردين</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ $suppliers->where('status', 'active')->count() }}</div>
            <div class="stats-label">الموردين النشطين</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $suppliers->sum('products_count') }}</div>
            <div class="stats-label">إجمالي المنتجات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $suppliers->where('country', 'العراق')->count() }}</div>
            <div class="stats-label">موردين محليين</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phonetech.suppliers.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم المورد أو الشركة">
                </div>
                <div class="col-md-3">
                    <label class="form-label">البلد</label>
                    <select class="form-select" name="country">
                        <option value="">جميع البلدان</option>
                        @foreach($suppliers->pluck('country')->unique() as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary d-block w-100">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Suppliers Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المورد</th>
                        <th>الشركة</th>
                        <th>البلد</th>
                        <th>رقم الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>عدد المنتجات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    {{ substr($supplier->name, 0, 1) }}
                                </div>
                                <strong>{{ $supplier->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $supplier->company_name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $supplier->country }}</span>
                        </td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $supplier->products_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($supplier->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('phonetech.suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('phonetech.suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('phonetech.suppliers.destroy', $supplier) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-truck fa-3x mb-3"></i>
                            <p>لا توجد موردين مطابقين للبحث</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $suppliers->links() }}
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
                        <a href="{{ route('phonetech.suppliers.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>
                            إضافة مورد جديد
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportSuppliers()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير إلى Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#importSuppliersModal">
                            <i class="fas fa-upload me-1"></i>
                            استيراد موردين
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

<!-- Import Suppliers Modal -->
<div class="modal fade" id="importSuppliersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">استيراد موردين من Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.suppliers.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ملف Excel</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">يجب أن يحتوي الملف على الأعمدة: الاسم، الشركة، الهاتف، البريد الإلكتروني، البلد</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting">
                            <label class="form-check-label" for="updateExisting">
                                تحديث الموردين الموجودين
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-info">استيراد الموردين</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportSuppliers() {
    window.location.href = '{{ route("phonetech.suppliers.export") }}?' + new URLSearchParams(window.location.search);
}
</script>
@endpush

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endsection
