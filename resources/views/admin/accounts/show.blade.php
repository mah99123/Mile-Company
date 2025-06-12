@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Account Details') }}</h5>
                    <div>
                        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-sm btn-primary">{{ __('Edit Account') }}</a>
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to Accounts') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">{{ __('Account Information') }}</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('Account Name') }}</th>
                                    <td>{{ $account->account_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Account Number') }}</th>
                                    <td>{{ $account->account_number }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Account Type') }}</th>
                                    <td>{{ $account->account_type }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <td>{{ $account->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Current Balance') }}</th>
                                    <td>{{ number_format($balance, 2) }} {{ $account->currency }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Status') }}</th>
                                    <td>
                                        @if($account->status == 'active')
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created At') }}</th>
                                    <td>{{ $account->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Last Updated') }}</th>
                                    <td>{{ $account->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">{{ __('Account Balance History') }}</h6>
                            <canvas id="balanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">{{ __('Recent Transactions') }}</h6>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Description') }}</th>
                                            <th>{{ __('Reference') }}</th>
                                            <th>{{ __('Debit') }}</th>
                                            <th>{{ __('Credit') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($entries as $entry)
                                            <tr>
                                                <td>{{ $entry->transaction_date->format('Y-m-d') }}</td>
                                                <td>{{ $entry->description }}</td>
                                                <td>{{ $entry->reference }}</td>
                                                <td>{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '' }}</td>
                                                <td>{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '' }}</td>
                                                <td>{{ number_format($entry->running_balance, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('No transactions found.') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3">
                                {{ $entries->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('balanceChart').getContext('2d');
        
        // Sample data - in a real app, this would come from the backend
        const balanceHistory = @json($balanceHistory);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: balanceHistory.dates,
                datasets: [{
                    label: '{{ __("Account Balance") }}',
                    data: balanceHistory.balances,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection
