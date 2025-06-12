@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-balance-scale me-2"></i>
        ميزان المراجعة
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-secondary me-2" onclick="window.print()">
            <i class="fas fa-print me-1"></i>
            طباعة
        </button>
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للحسابات
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">ميزان المراجعة كما في {{ date('Y-m-d') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>رقم الحساب</th>
                        <th>اسم الحساب</th>
                        <th>نوع الحساب</th>
                        <th>المنصة</th>
                        <th class="text-end">مدين</th>
                        <th class="text-end">دائن</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedAccounts as $type => $accounts)
                        <tr class="table-secondary">
                            <td colspan="6" class="fw-bold">
                                @switch($type)
                                    @case('Asset') الأصول @break
                                    @case('Liability') الخصوم @break
                                    @case('Equity') حقوق الملكية @break
                                    @case('Revenue') الإيرادات @break
                                    @case('Expense') المصروفات @break
                                @endswitch
                            </td>
                        </tr>
                        @foreach($accounts as $account)
                            @php
                                $balance = $balances[$account->account_id] ?? 0;
                                $isDebitBalance = in_array($account->account_type, ['Asset', 'Expense']);
                            @endphp
                            <tr>
                                <td>{{ $account->account_id }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>
                                    @switch($account->account_type)
                                        @case('Asset') أصل @break
                                        @case('Liability') خصم @break
                                        @case('Equity') حقوق ملكية @break
                                        @case('Revenue') إيراد @break
                                        @case('Expense') مصروف @break
                                    @endswitch
                                </td>
                                <td>{{ $account->platform }}</td>
                                <td class="text-end">
                                    @if(($isDebitBalance && $balance > 0) || (!$isDebitBalance && $balance < 0))
                                        {{ number_format(abs($balance), 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(($isDebitBalance && $balance < 0) || (!$isDebitBalance && $balance > 0))
                                        {{ number_format(abs($balance), 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="4">الإجمالي</th>
                        <th class="text-end">{{ number_format($totalDebits, 2) }}</th>
                        <th class="text-end">{{ number_format($totalCredits, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        @if(abs($totalDebits - $totalCredits) > 0.01)
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                تحذير: الميزان غير متوازن! الفرق: {{ number_format(abs($totalDebits - $totalCredits), 2) }} دينار
            </div>
        @else
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle me-2"></i>
                الميزان متوازن بنجاح
            </div>
        @endif
    </div>
</div>
@endsection
