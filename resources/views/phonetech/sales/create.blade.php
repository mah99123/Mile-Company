@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إنشاء فاتورة بيع جديدة
    </h1>
    <a href="{{ route('phonetech.sales.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للقائمة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('phonetech.sales.store') }}" id="saleForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">اسم العميل <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                               id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">المنتج <span class="text-danger">*</span></label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">اختر المنتج</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}" 
                                        data-price="{{ $product->selling_price }}"
                                        data-stock="{{ $product->quantity_in_stock }}"
                                        {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ number_format($product->selling_price, 2) }} دينار (متوفر: {{ $product->quantity_in_stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sale_date" class="form-label">تاريخ البيع <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('sale_date') is-invalid @enderror" 
                               id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                        @error('sale_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="total_amount" class="form-label">المبلغ الإجمالي (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" 
                               id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required readonly>
                        @error('total_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="down_payment" class="form-label">الدفعة المقدمة (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('down_payment') is-invalid @enderror" 
                               id="down_payment" name="down_payment" value="{{ old('down_payment') }}" required>
                        @error('down_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="installment_period_months" class="form-label">فترة التقسيط (شهر) <span class="text-danger">*</span></label>
                        <select class="form-select @error('installment_period_months') is-invalid @enderror" 
                                id="installment_period_months" name="installment_period_months" required>
                            <option value="">اختر المدة</option>
                            @for($i = 1; $i <= 60; $i++)
                                <option value="{{ $i }}" {{ old('installment_period_months') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'شهر' : 'شهر' }}
                                </option>
                            @endfor
                        </select>
                        @error('installment_period_months')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Calculation Summary -->
            <div class="card bg-light mb-3" id="calculationSummary" style="display: none;">
                <div class="card-body">
                    <h6 class="card-title">ملخص الحساب</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>المبلغ الإجمالي:</strong>
                            <div id="summaryTotal" class="text-primary fs-5">0 دينار</div>
                        </div>
                        <div class="col-md-3">
                            <strong>الدفعة المقدمة:</strong>
                            <div id="summaryDownPayment" class="text-success fs-5">0 دينار</div>
                        </div>
                        <div class="col-md-3">
                            <strong>المبلغ المتبقي:</strong>
                            <div id="summaryRemaining" class="text-danger fs-5">0 دينار</div>
                        </div>
                        <div class="col-md-3">
                            <strong>قيمة القسط الشهري:</strong>
                            <div id="summaryInstallment" class="text-info fs-5">0 دينار</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('phonetech.sales.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    إنشاء الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const totalAmountInput = document.getElementById('total_amount');
    const downPaymentInput = document.getElementById('down_payment');
    const installmentPeriodSelect = document.getElementById('installment_period_months');
    const calculationSummary = document.getElementById('calculationSummary');
    
    // Update total amount when product is selected
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            totalAmountInput.value = price;
            updateCalculations();
        } else {
            totalAmountInput.value = '';
            calculationSummary.style.display = 'none';
        }
    });
    
    // Update calculations when inputs change
    downPaymentInput.addEventListener('input', updateCalculations);
    installmentPeriodSelect.addEventListener('change', updateCalculations);
    
    function updateCalculations() {
        const total = parseFloat(totalAmountInput.value) || 0;
        const downPayment = parseFloat(downPaymentInput.value) || 0;
        const months = parseInt(installmentPeriodSelect.value) || 0;
        
        if (total > 0 && downPayment >= 0 && months > 0) {
            const remaining = total - downPayment;
            const monthlyInstallment = remaining / months;
            
            document.getElementById('summaryTotal').textContent = total.toLocaleString() + ' دينار';
            document.getElementById('summaryDownPayment').textContent = downPayment.toLocaleString() + ' دينار';
            document.getElementById('summaryRemaining').textContent = remaining.toLocaleString() + ' دينار';
            document.getElementById('summaryInstallment').textContent = monthlyInstallment.toLocaleString() + ' دينار';
            
            calculationSummary.style.display = 'block';
        } else {
            calculationSummary.style.display = 'none';
        }
    }
});
</script>
@endpush
@endsection
