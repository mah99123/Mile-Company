@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إنشاء قيد محاسبي جديد
    </h1>
    <a href="{{ route('admin.accounts.journal-entries') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة لدفتر اليومية
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.accounts.store-journal-entry') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                               id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_number" class="form-label">رقم المرجع</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" value="{{ old('reference_number') }}" 
                               placeholder="JE-{{ date('Y-m-d') }}-{{ time() }}">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">وصف القيد <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="debit_account" class="form-label">الحساب المدين <span class="text-danger">*</span></label>
                        <select class="form-select @error('debit_account') is-invalid @enderror" 
                                id="debit_account" name="debit_account" required>
                            <option value="">اختر الحساب المدين</option>
                            @foreach($accounts->groupBy('account_type') as $type => $typeAccounts)
                                <optgroup label="
                                    @switch($type)
                                        @case('Asset') الأصول @break
                                        @case('Liability') الخصوم @break
                                        @case('Equity') حقوق الملكية @break
                                        @case('Revenue') الإيرادات @break
                                        @case('Expense') المصروفات @break
                                    @endswitch
                                ">
                                    @foreach($typeAccounts as $account)
                                        <option value="{{ $account->account_id }}" 
                                                {{ old('debit_account') == $account->account_id ? 'selected' : '' }}>
                                            {{ $account->account_name }} ({{ $account->platform }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('debit_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="credit_account" class="form-label">الحساب الدائن <span class="text-danger">*</span></label>
                        <select class="form-select @error('credit_account') is-invalid @enderror" 
                                id="credit_account" name="credit_account" required>
                            <option value="">اختر الحساب الدائن</option>
                            @foreach($accounts->groupBy('account_type') as $type => $typeAccounts)
                                <optgroup label="
                                    @switch($type)
                                        @case('Asset') الأصول @break
                                        @case('Liability') الخصوم @break
                                        @case('Equity') حقوق الملكية @break
                                        @case('Revenue') الإيرادات @break
                                        @case('Expense') المصروفات @break
                                    @endswitch
                                ">
                                    @foreach($typeAccounts as $account)
                                        <option value="{{ $account->account_id }}" 
                                                {{ old('credit_account') == $account->account_id ? 'selected' : '' }}>
                                            {{ $account->account_name }} ({{ $account->platform }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('credit_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">المبلغ (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount') }}" min="0.01" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Preview Section -->
            <div class="card bg-light mb-3" id="preview-section" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">معاينة القيد</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الحساب</th>
                                    <th>مدين</th>
                                    <th>دائن</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="preview-debit-account">-</td>
                                    <td id="preview-debit-amount">-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td id="preview-credit-account">-</td>
                                    <td>-</td>
                                    <td id="preview-credit-amount">-</td>
                                </tr>
                                <tr class="table-info">
                                    <td><strong>الإجمالي</strong></td>
                                    <td><strong id="preview-total-debit">-</strong></td>
                                    <td><strong id="preview-total-credit">-</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.accounts.journal-entries') }}" class="btn btn-secondary">إلغاء</a>
                <button type="button" class="btn btn-info" onclick="previewEntry()">
                    <i class="fas fa-eye me-1"></i>
                    معاينة القيد
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ القيد
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewEntry() {
    const debitAccountSelect = document.getElementById('debit_account');
    const creditAccountSelect = document.getElementById('credit_account');
    const amount = document.getElementById('amount').value;
    
    if (!debitAccountSelect.value || !creditAccountSelect.value || !amount) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    const debitAccountText = debitAccountSelect.options[debitAccountSelect.selectedIndex].text;
    const creditAccountText = creditAccountSelect.options[creditAccountSelect.selectedIndex].text;
    
    document.getElementById('preview-debit-account').textContent = debitAccountText;
    document.getElementById('preview-credit-account').textContent = creditAccountText;
    document.getElementById('preview-debit-amount').textContent = parseFloat(amount).toLocaleString('ar-SA', {minimumFractionDigits: 2}) + ' دينار';
    document.getElementById('preview-credit-amount').textContent = parseFloat(amount).toLocaleString('ar-SA', {minimumFractionDigits: 2}) + ' دينار';
    document.getElementById('preview-total-debit').textContent = parseFloat(amount).toLocaleString('ar-SA', {minimumFractionDigits: 2}) + ' دينار';
    document.getElementById('preview-total-credit').textContent = parseFloat(amount).toLocaleString('ar-SA', {minimumFractionDigits: 2}) + ' دينار';
    
    document.getElementById('preview-section').style.display = 'block';
}

// Prevent selecting same account for debit and credit
document.getElementById('debit_account').addEventListener('change', function() {
    const creditSelect = document.getElementById('credit_account');
    const selectedValue = this.value;
    
    Array.from(creditSelect.options).forEach(option => {
        if (option.value === selectedValue) {
            option.disabled = true;
        } else {
            option.disabled = false;
        }
    });
});

document.getElementById('credit_account').addEventListener('change', function() {
    const debitSelect = document.getElementById('debit_account');
    const selectedValue = this.value;
    
    Array.from(debitSelect.options).forEach(option => {
        if (option.value === selectedValue) {
            option.disabled = true;
        } else {
            option.disabled = false;
        }
    });
});
</script>
@endpush
@endsection
