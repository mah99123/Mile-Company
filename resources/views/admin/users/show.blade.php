@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>
        تفاصيل المستخدم: {{ $user->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل المستخدم
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- User Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-2">
                    @if($user->status == 'active')
                        <span class="badge bg-success fs-6">نشط</span>
                    @else
                        <span class="badge bg-secondary fs-6">غير نشط</span>
                    @endif
                    
                    @if($user->email_verified_at)
                        <span class="badge bg-info fs-6">مؤكد</span>
                    @else
                        <span class="badge bg-warning fs-6">غير مؤكد</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الاتصال</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">البريد الإلكتروني</label>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">رقم الهاتف</label>
                    <p class="mb-0">{{ $user->phone ?? 'غير محدد' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">القسم</label>
                    <p class="mb-0">{{ $user->department ?? 'غير محدد' }}</p>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted">تاريخ التسجيل</label>
                    <p class="mb-0">{{ $user->created_at->format('Y-m-d H:i') }}</p>
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
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل المستخدم
                    </a>
                    <button type="button" class="btn btn-success" onclick="sendPasswordReset()">
                        <i class="fas fa-key me-1"></i>
                        إعادة تعيين كلمة المرور
                    </button>
                    @if($user->status == 'active')
                        <button type="button" class="btn btn-warning" onclick="toggleUserStatus('inactive')">
                            <i class="fas fa-user-slash me-1"></i>
                            إلغاء تفعيل المستخدم
                        </button>
                    @else
                        <button type="button" class="btn btn-success" onclick="toggleUserStatus('active')">
                            <i class="fas fa-user-check me-1"></i>
                            تفعيل المستخدم
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Roles and Permissions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">الأدوار والصلاحيات</h5>
            </div>
            <div class="card-body">
                @if($user->roles->count() > 0)
                    <div class="row">
                        @foreach($user->roles as $role)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary">{{ $role->name }}</h6>
                                    <small class="text-muted">{{ $role->permissions->count() }} صلاحية</small>
                                    <div class="mt-2">
                                        @foreach($role->permissions->take(5) as $permission)
                                            <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 5)
                                            <span class="badge bg-secondary">+{{ $role->permissions->count() - 5 }} أخرى</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-shield fa-3x mb-3"></i>
                        <p>لم يتم تعيين أي أدوار لهذا المستخدم</p>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة أدوار
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات النشاط</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h4 class="text-primary">{{ $user->campaigns->count() }}</h4>
                        <small class="text-muted">الحملات المنشأة</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h4 class="text-success">{{ $user->sales->count() }}</h4>
                        <small class="text-muted">المبيعات المسجلة</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h4 class="text-info">{{ $user->carImports->count() }}</h4>
                        <small class="text-muted">عمليات الاستيراد</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">النشاط الأخير</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($user->campaigns->count() > 0)
                        @foreach($user->campaigns->latest()->take(3) as $campaign)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>إنشاء حملة إعلانية</h6>
                                    <p class="text-muted mb-1">{{ $campaign->page_name }}</p>
                                    <small class="text-muted">{{ $campaign->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if($user->sales->count() > 0)
                        @foreach($user->sales->latest()->take(2) as $sale)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>تسجيل عملية بيع</h6>
                                    <p class="text-muted mb-1">فاتورة رقم: {{ $sale->invoice_id }}</p>
                                    <small class="text-muted">{{ $sale->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if($user->carImports->count() > 0)
                        @foreach($user->carImports->latest()->take(2) as $import)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6>إدارة عملية استيراد</h6>
                                    <p class="text-muted mb-1">لوت رقم: {{ $import->lot_number }}</p>
                                    <small class="text-muted">{{ $import->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if($user->campaigns->count() == 0 && $user->sales->count() == 0 && $user->carImports->count() == 0)
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <p>لا يوجد نشاط مسجل لهذا المستخدم</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sendPasswordReset() {
    if (confirm('هل أنت متأكد من إرسال رابط إعادة تعيين كلمة المرور؟')) {
        fetch('{{ route("admin.users.send-password-reset", $user) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال رابط إعادة تعيين كلمة المرور بنجاح');
            } else {
                alert('حدث خطأ أثناء إرسال الرابط');
            }
        });
    }
}

function toggleUserStatus(status) {
    const action = status === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
    if (confirm(`هل أنت متأكد من ${action} هذا المستخدم؟`)) {
        fetch('{{ route("admin.users.toggle-status", $user) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث حالة المستخدم');
            }
        });
    }
}
</script>
@endpush

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

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
