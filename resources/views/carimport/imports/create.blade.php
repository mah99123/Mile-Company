@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إضافة عملية استيراد جديدة
    </h1>
    <a href="{{ route('carimport.imports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للقائمة
    </a>
</div>

<form method="POST" action="{{ route('carimport.imports.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات المزاد الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">نوع المزاد <span class="text-danger">*</span></label>
                                <select class="form-select @error('auction_type') is-invalid @enderror" name="auction_type" required>
                                    <option value="">اختر نوع المزاد</option>
                                    <option value="Copart" {{ old('auction_type') == 'Copart' ? 'selected' : '' }}>Copart</option>
                                    <option value="IAAI" {{ old('auction_type') == 'IAAI' ? 'selected' : '' }}>IAAI</option>
                                    <option value="Manheim" {{ old('auction_type') == 'Manheim' ? 'selected' : '' }}>Manheim</option>
                                    <option value="Other" {{ old('auction_type') == 'Other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('auction_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">رقم اللوت <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lot_number') is-invalid @enderror" 
                                       name="lot_number" value="{{ old('lot_number') }}" required>
                                @error('lot_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">شركة المشتري <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('buyer_company') is-invalid @enderror" 
                                       name="buyer_company" value="{{ old('buyer_company') }}" required>
                                @error('buyer_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">شركة الشحن <span class="text-danger">*</span></label>
                                <select class="form-select @error('shipping_company') is-invalid @enderror" name="shipping_company" required>
                                    <option value="">اختر شركة الشحن</option>
                                    <option value="شركة الخليج للشحن" {{ old('shipping_company') == 'شركة الخليج للشحن' ? 'selected' : '' }}>شركة الخليج للشحن</option>
                                    <option value="شركة النجم للشحن" {{ old('shipping_company') == 'شركة النجم للشحن' ? 'selected' : '' }}>شركة النجم للشحن</option>
                                    <option value="شركة الأطلسي للشحن" {{ old('shipping_company') == 'شركة الأطلسي للشحن' ? 'selected' : '' }}>شركة الأطلسي للشحن</option>
                                </select>
                                @error('shipping_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">المعلومات المالية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">المبلغ الإجمالي مع النقل <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('total_with_transfer') is-invalid @enderror" 
                                       name="total_with_transfer" value="{{ old('total_with_transfer') }}" required>
                                @error('total_with_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">المبلغ المستلم <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('amount_received') is-invalid @enderror" 
                                       name="amount_received" value="{{ old('amount_received') }}" required>
                                @error('amount_received')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">المبلغ المتبقي <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" 
                                       name="remaining_amount" value="{{ old('remaining_amount') }}" required>
                                @error('remaining_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تاريخ فاتورة المزاد <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('auction_invoice_date') is-invalid @enderror" 
                                       name="auction_invoice_date" value="{{ old('auction_invoice_date') }}" required>
                                @error('auction_invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">رقم فاتورة المزاد <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('auction_invoice_number') is-invalid @enderror" 
                                       name="auction_invoice_number" value="{{ old('auction_invoice_number') }}" required>
                                @error('auction_invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">رقم عقد المكتب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_contract_number') is-invalid @enderror" 
                                       name="office_contract_number" value="{{ old('office_contract_number') }}" required>
                                @error('office_contract_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تاريخ عقد المكتب <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('office_contract_date') is-invalid @enderror" 
                                       name="office_contract_date" value="{{ old('office_contract_date') }}" required>
                                @error('office_contract_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Costs -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">تكاليف الشحن والعمولات</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">مبلغ فاتورة المكتب <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('office_invoice_amount') is-invalid @enderror" 
                                       name="office_invoice_amount" value="{{ old('office_invoice_amount') }}" required>
                                @error('office_invoice_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">المتبقي من فاتورة المكتب <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('remaining_office_invoice') is-invalid @enderror" 
                                       name="remaining_office_invoice" value="{{ old('remaining_office_invoice') }}" required>
                                @error('remaining_office_invoice')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تكلفة الشحن للشركة <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('company_shipping_cost') is-invalid @enderror" 
                                       name="company_shipping_cost" value="{{ old('company_shipping_cost') }}" required>
                                @error('company_shipping_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">تكلفة الشحن للعميل <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('customer_shipping_cost') is-invalid @enderror" 
                                       name="customer_shipping_cost" value="{{ old('customer_shipping_cost') }}" required>
                                @error('customer_shipping_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ربح الشحن <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('shipping_profit') is-invalid @enderror" 
                                       name="shipping_profit" value="{{ old('shipping_profit') }}" required>
                                @error('shipping_profit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">عمولة المكتب <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('office_commission') is-invalid @enderror" 
                                       name="office_commission" value="{{ old('office_commission') }}" required>
                                @error('office_commission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Assignment and Currency -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">التخصيص والعملة</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الموظف المسؤول <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_assigned') is-invalid @enderror" name="employee_assigned" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_assigned') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_assigned')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">العملة <span class="text-danger">*</span></label>
                        <select class="form-select @error('currency') is-invalid @enderror" name="currency" required>
                            <option value="">اختر العملة</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="IQD" {{ old('currency') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Shipping Dates -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">تواريخ الشحن</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">تاريخ السحب</label>
                        <input type="date" class="form-control @error('pull_date') is-invalid @enderror" 
                               name="pull_date" value="{{ old('pull_date') }}">
                        @error('pull_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ الشحن</label>
                        <input type="date" class="form-control @error('shipping_date') is-invalid @enderror" 
                               name="shipping_date" value="{{ old('shipping_date') }}">
                        @error('shipping_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ الوصول</label>
                        <input type="date" class="form-control @error('arrival_date') is-invalid @enderror" 
                               name="arrival_date" value="{{ old('arrival_date') }}">
                        @error('arrival_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات المستلم</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">اسم المستلم</label>
                        <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                               name="recipient_name" value="{{ old('recipient_name') }}">
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">رقم هاتف المستلم</label>
                        <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                               name="recipient_phone" value="{{ old('recipient_phone') }}">
                        @error('recipient_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ الاستلام</label>
                        <input type="date" class="form-control @error('recipient_receive_date') is-invalid @enderror" 
                               name="recipient_receive_date" value="{{ old('recipient_receive_date') }}">
                        @error('recipient_receive_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">رقم الحاوية</label>
                        <input type="text" class="form-control @error('container_number') is-invalid @enderror" 
                               name="container_number" value="{{ old('container_number') }}">
                        @error('container_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">ملاحظات إضافية</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="4">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('carimport.imports.index') }}" class="btn btn-secondary">إلغاء</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            حفظ عملية الاستيراد
        </button>
    </div>
</form>

@push('scripts')
<script>
// Auto calculate remaining amount
document.addEventListener('DOMContentLoaded', function() {
    const totalInput = document.querySelector('input[name="total_with_transfer"]');
    const receivedInput = document.querySelector('input[name="amount_received"]');
    const remainingInput = document.querySelector('input[name="remaining_amount"]');
    
    function calculateRemaining() {
        const total = parseFloat(totalInput.value) || 0;
        const received = parseFloat(receivedInput.value) || 0;
        const remaining = total - received;
        remainingInput.value = remaining.toFixed(2);
    }
    
    totalInput.addEventListener('input', calculateRemaining);
    receivedInput.addEventListener('input', calculateRemaining);
});
</script>
@endpush
@endsection
