@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-pie me-2"></i>
        الميزانية العمومية
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

<div class="row">
    <!-- Assets -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">الأصول</h5>
            </div>
            <div class="card-body">
                @if(isset($groupedAccounts['Asset']))
                    @foreach($groupedAccounts['Asset'] as $account)
                        @php
                            $balance = $balances[$account->account_id] ?? 0;
                        @endphp
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $account->account_name }}</span>
                            <span>{{ number_format($balance, 2) }} دينار</span>
                        </div>
                    @endforeach
                @endif
                <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold">
                    <span>إجمالي الأصول</span>
                    <span>{{ number_format($totalAssets, 2) }} دينار</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liabilities and Equity -->
    <div class="col-md-6">
        <!-- Liabilities -->
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">الخصوم</h5>
            </div>
            <div class="card-body">
                @if(isset($groupedAccounts['Liability']))
                    @foreach($groupedAccounts['Liability'] as $account)
                        @php
                            $balance = $balances[$account->account_id] ?? 0;
                        @endphp
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $account->account_name }}</span>
                            <span>{{ number_format($balance, 2) }} دينار</span>
                        </div>
                    @endforeach
                @endif
                <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold">
                    <span>إجمالي الخصوم</span>
                    <span>{{ number_format($totalLiabilities, 2) }} دينار</span>
                </div>
            </div>
        </div>
        
        <!-- Equity -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">حقوق الملكية</h5>
            </div>
            <div class="card-body">
                @if(isset($groupedAccounts['Equity']))
                    @foreach($groupedAccounts['Equity'] as $account)
                        @php
                            $balance = $balances[$account->account_id] ?? 0;
                        @endphp
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $account->account_name }}</span>
                            <span>{{ number_format($balance, 2) }} دينار</span>
                        </div>
                    @endforeach
                @endif
                <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold">
                    <span>إجمالي حقوق الملكية</span>
                    <span>{{ number_format($totalEquity, 2) }} دينار</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">ملخص الميزانية العمومية</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-2">
                            <span class="fw-bold">إجمالي الأصول:</span>
                            <span class="fw-bold">{{ number_format($totalAssets, 2) }} دينار</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-2">
                            <span class="fw-bold">إجمالي الخصوم وحقوق الملكية:</span>
                            <span class="fw-bold">{{ number_format($totalLiabilities + $totalEquity, 2) }} دينار</span>
                        </div>
                    </div>
                </div>
                
                @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) > 0.01)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        تحذير: الميزانية غير متوازنة! الفرق: {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2) }} دينار
                    </div>
                @else
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        الميزانية العمومية متوازنة بنجاح
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
