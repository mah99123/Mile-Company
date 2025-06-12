@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-shield me-2"></i>
        إدارة الأدوار والصلاحيات
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة دور جديد
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $roles->count() }}</div>
            <div class="stats-label">إجمالي الأدوار</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ $roles->sum(function($role) { return $role->users->count(); }) }}</div>
            <div class="stats-label">المستخدمين المعينين</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $roles->sum(function($role) { return $role->permissions->count(); }) }}</div>
            <div class="stats-label">إجمالي الصلاحيات</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">4</div>
            <div class="stats-label">المنصات المدارة</div>
        </div>
    </div>
</div>

<!-- Roles Grid -->
<div class="row">
    @foreach($roles as $role)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $role->name }}</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.roles.show', $role) }}">
                            <i class="fas fa-eye me-1"></i> عرض التفاصيل
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.roles.edit', $role) }}">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                    <i class="fas fa-trash me-1"></i> حذف
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">عدد المستخدمين</small>
                    <h4 class="text-primary">{{ $role->users->count() }}</h4>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">عدد الصلاحيات</small>
                    <h6 class="text-success">{{ $role->permissions->count() }} صلاحية</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">الصلاحيات الرئيسية</small>
                    <div class="mt-2">
                        @foreach($role->permissions->take(3) as $permission)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span class="badge bg-secondary">+{{ $role->permissions->count() - 3 }} أخرى</span>
                        @endif
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>تم الإنشاء: {{ $role->created_at->format('Y-m-d') }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $roles->links() }}
</div>

<!-- Permissions Overview Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نظرة عامة على الصلاحيات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">صلاحيات منصة ميم</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>عرض الحملات</li>
                            <li><i class="fas fa-check text-success me-2"></i>إنشاء حملات</li>
                            <li><i class="fas fa-check text-success me-2"></i>تعديل الحملات</li>
                            <li><i class="fas fa-check text-success me-2"></i>حذف الحملات</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">صلاحيات فون تك</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>إدارة المنتجات</li>
                            <li><i class="fas fa-check text-success me-2"></i>إدارة المبيعات</li>
                            <li><i class="fas fa-check text-success me-2"></i>إدارة الموردين</li>
                            <li><i class="fas fa-check text-success me-2"></i>تقارير المبيعات</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info">صلاحيات استيراد السيارات</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>إدارة الاستيراد</li>
                            <li><i class="fas fa-check text-success me-2"></i>تتبع الشحنات</li>
                            <li><i class="fas fa-check text-success me-2"></i>إدارة العملاء</li>
                            <li><i class="fas fa-check text-success me-2"></i>تقارير الاستيراد</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">صلاحيات الإدارة</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>إدارة المستخدمين</li>
                            <li><i class="fas fa-check text-success me-2"></i>إدارة الأدوار</li>
                            <li><i class="fas fa-check text-success me-2"></i>التقارير المالية</li>
                            <li><i class="fas fa-check text-success me-2"></i>إعدادات النظام</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
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
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>
                            إضافة دور جديد
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#permissionsModal">
                            <i class="fas fa-list me-1"></i>
                            عرض جميع الصلاحيات
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.roles.export') }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير الأدوار
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#bulkAssignModal">
                            <i class="fas fa-users me-1"></i>
                            تعيين مجمع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعيين أدوار مجمع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.roles.bulk-assign') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اختر المستخدمين</label>
                        <select class="form-select" name="user_ids[]" multiple required>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اضغط Ctrl لاختيار عدة مستخدمين</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اختر الدور</label>
                        <select class="form-select" name="role_id" required>
                            <option value="">اختر الدور</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="replace_existing" id="replaceExisting">
                            <label class="form-check-label" for="replaceExisting">
                                استبدال الأدوار الموجودة
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تعيين الأدوار</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportRoles() {
    window.location.href = '{{ route("admin.roles.export") }}';
}
</script>
@endpush
@endsection
