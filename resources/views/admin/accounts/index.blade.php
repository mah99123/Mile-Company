@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calculator me-2"></i>
        إدارة الحسابات المحاسبية
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                إضافة حساب جديد
            </a>
            <a href="{{ route('admin.accounts.create-journal-entry') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>
                قيد محاسبي جديد
            </a>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.accounts.trial-balance') }}" class="btn btn-outline-info">
                <i class="fas fa-balance-scale me-1"></i>
                ميزان المراجعة
            </a>
            <a href="{{ route('admin.accounts.balance-sheet') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-pie me-1"></i>
                الميزانية العمومية
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stats-number">{{ $accountsByType['Asset']->sum('balance') ?? 0 }}</div>
            <div class="stats-label">إجمالي الأصول (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stats-number">{{ $accountsByType['Liability']->sum('balance') ?? 0 }}</div>
            <div class="stats-label">إجمالي الخصوم (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stats-number">{{ $accountsByType['Revenue']->sum('balance') ?? 0 }}</div>
            <div class="stats-label">إجمالي الإيرادات (دينار)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
            <div class="stats-number">{{ $accountsByType['Expense']->sum('balance') ?? 0 }}</div>
            <div class="stats-label">إجمالي المصروفات (دينار)</div>
        </div>
    </div>
</div>

<!-- Accounts by Platform -->
<div class="row mb-4">
    @foreach($accountsByPlatform as $platform => $platformAccounts)
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card">
            <div class="card-header 
                @switch($platform)
                    @case('Meym') bg-primary @break
                    @case('PhoneTech') bg-success @break
                    @case('CarImport') bg-info @break
                    @default bg-secondary @break
                @endswitch
                text-white">
                <h6 class="card-title mb-0">
                    @switch($platform)
                        @case('Meym')
                            <i class="fas fa-bullhorn me-2"></i>منصة ميم
                            @break
                        @case('PhoneTech')
                            <i class="fas fa-mobile-alt me-2"></i>محمد فون تك
                            @break
                        @case('CarImport')
                            <i class="fas fa-car me-2"></i>استيراد السيارات
                            @break
                        @default
                            <i class="fas fa-building me-2"></i>عام
                            @break
                    @endswitch
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>عدد الحسابات:</strong> {{ $platformAccounts->count() }}</p>
                <p class="mb-0"><strong>إجمالي الرصيد:</strong> {{ number_format($platformAccounts->sum('balance'), 2) }} دينار</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Accounts Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">دليل الحسابات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الحساب</th>
                        <th>اسم الحساب</th>
                        <th>نوع الحساب</th>
                        <th>المنصة</th>
                        <th>الرصيد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountsByType as $type => $typeAccounts)
                        <tr class="table-secondary">
                            <td colspan="6">
                                <strong>
                                    @switch($type)
                                        @case('Asset') الأصول @break
                                        @case('Liability') الخصوم @break
                                        @case('Equity') حقوق الملكية @break
                                        @case('Revenue') الإيرادات @break
                                        @case('Expense') المصروفات @break
                                    @endswitch
                                </strong>
                            </td>
                        </tr>
                        @foreach($typeAccounts as $account)
                        <tr>
                            <td><code>{{ $account->account_id }}</code></td>
                            <td>{{ $account->account_name }}</td>
                            <td>
                                <span class="badge 
                                    @switch($account->account_type)
                                        @case('Asset') bg-success @break
                                        @case('Liability') bg-warning @break
                                        @case('Equity') bg-info @break
                                        @case('Revenue') bg-primary @break
                                        @case('Expense') bg-danger @break
                                    @endswitch
                                ">
                                    @switch($account->account_type)
                                        @case('Asset') أصل @break
                                        @case('Liability') خصم @break
                                        @case('Equity') حقوق ملكية @break
                                        @case('Revenue') إيراد @break
                                        @case('Expense') مصروف @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @switch($account->platform)
                                        @case('Meym') bg-primary @break
                                        @case('PhoneTech') bg-success @break
                                        @case('CarImport') bg-info @break
                                        @default bg-secondary @break
                                    @endswitch
                                ">
                                    @switch($account->platform)
                                        @case('Meym') ميم @break
                                        @case('PhoneTech') فون تك @break
                                        @case('CarImport') استيراد @break
                                        @default عام @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="{{ $account->balance >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($account->balance, 2) }} دينار
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.accounts.show', $account) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الحساب؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
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
                        <a href="{{ route('admin.accounts.journal-entries') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-book me-1"></i>
                            دفتر اليومية
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.accounts.trial-balance') }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-balance-scale me-1"></i>
                            ميزان المراجعة
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.accounts.balance-sheet') }}" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-chart-pie me-1"></i>
                            الميزانية العمومية
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="exportAccounts()">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير الحسابات
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportAccounts() {
    window.location.href = '{{ route("admin.accounts.export") }}';
}
</script>
@endpush
@endsection
