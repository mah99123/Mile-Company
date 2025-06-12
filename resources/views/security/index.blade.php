@extends('layouts.app')

@section('content')
<div class="container-fluid rtl">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">إعدادات الأمان</h1>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <!-- تغيير كلمة المرور -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>تغيير كلمة المرور</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('security.change-password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">كلمة المرور الحالية</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer small text-muted">
                    آخر تغيير لكلمة المرور: 
                    @if($user->password_changed_at)
                        {{ $user->password_changed_at->format('Y-m-d H:i') }}
                    @else
                        لم يتم تغيير كلمة المرور من قبل
                    @endif
                </div>
            </div>
        </div>

        <!-- المصادقة الثنائية -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>المصادقة الثنائية</h5>
                </div>
                <div class="card-body">
                    <p>المصادقة الثنائية توفر طبقة إضافية من الأمان لحسابك عن طريق طلب رمز تحقق إضافي عند تسجيل الدخول.</p>
                    
                    @if($user->two_factor_enabled)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-1"></i> المصادقة الثنائية مفعلة
                        </div>
                        <form action="{{ route('security.disable-2fa') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">تعطيل المصادقة الثنائية</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> المصادقة الثنائية غير مفعلة
                        </div>
                        <form action="{{ route('security.enable-2fa') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">تفعيل المصادقة الثنائية</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- سجل تسجيل الدخول -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>سجل تسجيل الدخول</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>عنوان IP</th>
                                    <th>المتصفح</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loginHistory as $login)
                                <tr>
                                    <td>{{ $login->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $login->ip_address }}</td>
                                    <td>{{ \Str::limit($login->user_agent, 30) }}</td>
                                    <td>
                                        @if($login->status == 'success')
                                            <span class="badge badge-success">ناجح</span>
                                        @else
                                            <span class="badge badge-danger">فاشل</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('security.activity-log') }}" class="btn btn-sm btn-primary">عرض سجل النشاط الكامل</a>
                </div>
            </div>
        </div>

        <!-- الجلسات النشطة -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>الجلسات النشطة</h5>
                </div>
                <div class="card-body">
                    <p>يمكنك تسجيل الخروج من جميع الأجهزة الأخرى التي قمت بتسجيل الدخول إليها.</p>
                    <form action="{{ route('security.logout-all-devices') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sign-out-alt mr-1"></i> تسجيل الخروج من جميع الأجهزة الأخرى
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
