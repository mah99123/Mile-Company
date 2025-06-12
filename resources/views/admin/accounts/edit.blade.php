@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Edit Account') }}</h5>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to Accounts') }}</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.accounts.update', $account->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-end">{{ __('Account Name') }}</label>
                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ old('account_name', $account->account_name) }}" required autofocus>
                                @error('account_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="account_number" class="col-md-4 col-form-label text-md-end">{{ __('Account Number') }}</label>
                            <div class="col-md-6">
                                <input id="account_number" type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ old('account_number', $account->account_number) }}" required>
                                @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="account_type" class="col-md-4 col-form-label text-md-end">{{ __('Account Type') }}</label>
                            <div class="col-md-6">
                                <select id="account_type" class="form-select @error('account_type') is-invalid @enderror" name="account_type" required>
                                    <option value="">{{ __('Select Account Type') }}</option>
                                    <option value="asset" {{ old('account_type', $account->account_type) == 'asset' ? 'selected' : '' }}>{{ __('Asset') }}</option>
                                    <option value="liability" {{ old('account_type', $account->account_type) == 'liability' ? 'selected' : '' }}>{{ __('Liability') }}</option>
                                    <option value="equity" {{ old('account_type', $account->account_type) == 'equity' ? 'selected' : '' }}>{{ __('Equity') }}</option>
                                    <option value="revenue" {{ old('account_type', $account->account_type) == 'revenue' ? 'selected' : '' }}>{{ __('Revenue') }}</option>
                                    <option value="expense" {{ old('account_type', $account->account_type) == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                                </select>
                                @error('account_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $account->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="currency" class="col-md-4 col-form-label text-md-end">{{ __('Currency') }}</label>
                            <div class="col-md-6">
                                <select id="currency" class="form-select @error('currency') is-invalid @enderror" name="currency" required>
                                    <option value="USD" {{ old('currency', $account->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', $account->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', $account->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="JPY" {{ old('currency', $account->currency) == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                    <option value="SAR" {{ old('currency', $account->currency) == 'SAR' ? 'selected' : '' }}>SAR - Saudi Riyal</option>
                                    <option value="AED" {{ old('currency', $account->currency) == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="status" class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select id="status" class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ old('status', $account->status) == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status', $account->status) == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Account') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
