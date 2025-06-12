@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-shield me-2"></i>
        تفاصيل الدور: {{ $role->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل الدور
        </a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Role Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الدور</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">اسم الدور</label>
                    <h5>{{ $role->name }}</h5>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">عدد الصلاحيات</label>
                    <h5 class="text-primary">{{ $role->permissions->count() }} صلاحية</h5>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">عدد المستخدمين</label>
                    <h5 class="text-success">{{ $role->users->count() }} مستخدم</h5>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted">تاريخ الإنشاء</label>
                    <p class="mb-0">{{ $role->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل الدور
                    </a>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignUsersModal">
                        <i class="fas fa-user-plus me-1"></i>
                        تعيين مستخدمين
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportRoleDetails()">
                        <i class="fas fa-download me-1"></i>
                        تصدير التفاصيل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Permissions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">الصلاحيات المخصصة</h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    @php
                        $groupedPermissions = $role->permissions->groupBy('module');
                    @endphp
                    <div class="row">
                        @foreach($groupedPermissions as $module => $permissions)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-primary mb-3">{{ $module }}</h6>
                                @foreach($permissions as $permission)
                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                        <p>لا توجد صلاحيات مخصصة لهذا الدور</p>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة صلاحيات
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assigned Users -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">المستخدمين المعينين</h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>القسم</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->department ?? '-' }}</td>
                                    <td>
                                        @if($user->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUserFromRole({{ $user->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>لا يوجد مستخدمين معينين لهذا الدور</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignUsersModal">
                            <i class="fas fa-user-plus me-1"></i>
                            تعيين مستخدمين
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Users Modal -->
<div class="modal fade" id="assignUsersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعيين مستخدمين للدور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.roles.assign-users', $role) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اختر المستخدمين</label>
                        <select class="form-select" name="user_ids[]" multiple required>
                            @foreach(\App\Models\User::whereNotIn('id', $role->users->pluck('id'))->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اضغط Ctrl لاختيار عدة مستخدمين</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تعيين المستخدمين</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function removeUserFromRole(userId) {
    if (confirm('هل أنت متأكد من إزالة هذا المستخدم من الدور؟')) {
        fetch(`{{ route('admin.roles.remove-user', $role) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء إزالة المستخدم');
            }
        });
    }
}

function exportRoleDetails() {
    window.location.href = `{{ route('admin.roles.export-details', $role) }}`;
}
</script>
@endpush

<style>
.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}
</style>
@endsection
