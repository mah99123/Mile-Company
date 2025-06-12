@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        تعديل عملية الاستيراد: {{ $carImport->lot_number }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('carimport.imports.show', $carImport) }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-eye me-1"></i>
            عرض التفاصيل
        </a>
        <a href="{{ route('carimport.imports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<form method="POST" action="{{ route('carimport.imports.update', $carImport) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                                    <option value="Copart" {{ old('auction_type', $carImport->auction_type) == 'Copart' ? 'selected' : '' }}>Copart</option>
                                    <option value="IAAI" {{ old('auction_type', $carImport->auction_type) == 'IAAI' ? 'selected' : '' }}>IAAI</option>
                                    <option value="Manheim" {{ old('auction_type', $carImport->auction_type) == 'Manheim' ? 'selected' : '' }}>Manheim</option>
                                    <option value="Other" {{ old('auction_type', $carImport->auction_type) == 'Other' ? 'selected' : '' }}>أخرى</option>
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
                                       name="lot_number" value="{{ old('lot_number', $carImport->lot_number) }}" required>
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
                                       name="buyer_company" value="{{ old('buyer_company', $carImport->buyer_company) }}" required>
                                @error('buyer_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">شركة الشحن <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('shipping_company') is-invalid @enderror" 
                                       name="shipping_company" value="{{ old('shipping_company', $carImport->shipping_company) }}" required>
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
                                       name="total_with_transfer" value="{{ old('total_with_transfer', $carImport->total_with_transfer) }}" required>
                                @error('total_with_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">المبلغ المستلم <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('amount_received') is-invalid @enderror" 
                                       name="amount_received" value="{{ old('amount_received', $carImport->amount_received) }}" required>
                                @error('amount_received')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">المبلغ المتبقي <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('remaining_amount') is-invalid @enderror" 
                                       name="remaining_amount" value="{{ old('remaining_amount', $carImport->remaining_amount) }}" required>
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
                                       name="auction_invoice_date" value="{{ old('auction_invoice_date', $carImport->auction_invoice_date->format('Y-m-d')) }}" required>
                                @error('auction_invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">رقم فاتورة المزاد <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('auction_invoice_number') is-invalid @enderror" 
                                       name="auction_invoice_number" value="{{ old('auction_invoice_number', $carImport->auction_invoice_number) }}" required>
                                @error('auction_invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">حالة الشحن والتواريخ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">حالة الشحن <span class="text-danger">*</span></label>
                                <select class="form-select @error('shipping_status') is-invalid @enderror" name="shipping_status" required>
                                    <option value="Pending" {{ old('shipping_status', $carImport->shipping_status) == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                                    <option value="Shipped" {{ old('shipping_status', $carImport->shipping_status) == 'Shipped' ? 'selected' : '' }}>قيد الشحن</option>
                                    <option value="Arrived" {{ old('shipping_status', $carImport->shipping_status) == 'Arrived' ? 'selected' : '' }}>وصل</option>
                                    <option value="Delivered" {{ old('shipping_status', $carImport->shipping_status) == 'Delivered' ? 'selected' : '' }}>تم التسليم</option>
                                </select>
                                @error('shipping_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">رقم الحاوية</label>
                                <input type="text" class="form-control @error('container_number') is-invalid @enderror" 
                                       name="container_number" value="{{ old('container_number', $carImport->container_number) }}">
                                @error('container_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">تاريخ السحب</label>
                                <input type="date" class="form-control @error('pull_date') is-invalid @enderror" 
                                       name="pull_date" value="{{ old('pull_date', $carImport->pull_date?->format('Y-m-d')) }}">
                                @error('pull_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">تاريخ الشحن</label>
                                <input type="date" class="form-control @error('shipping_date') is-invalid @enderror" 
                                       name="shipping_date" value="{{ old('shipping_date', $carImport->shipping_date?->format('Y-m-d')) }}">
                                @error('shipping_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">تاريخ الوصول</label>
                                <input type="date" class="form-control @error('arrival_date') is-invalid @enderror" 
                                       name="arrival_date" value="{{ old('arrival_date', $carImport->arrival_date?->format('Y-m-d')) }}">
                                @error('arrival_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Assignment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">التخصيص</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">الموظف المسؤول <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_assigned') is-invalid @enderror" name="employee_assigned" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_assigned', $carImport->employee_assigned) == $employee->id ? 'selected' : '' }}>
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
                            <option value="USD" {{ old('currency', $carImport->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="IQD" {{ old('currency', $carImport->currency) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="EUR" {{ old('currency', $carImport->currency) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                        @error('currency')
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
                               name="recipient_name" value="{{ old('recipient_name', $carImport->recipient_name) }}">
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">رقم هاتف المستلم</label>
                        <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                               name="recipient_phone" value="{{ old('recipient_phone', $carImport->recipient_phone) }}">
                        @error('recipient_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ الاستلام</label>
                        <input type="date" class="form-control @error('recipient_receive_date') is-invalid @enderror" 
                               name="recipient_receive_date" value="{{ old('recipient_receive_date', $carImport->recipient_receive_date?->format('Y-m-d')) }}">
                        @error('recipient_receive_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">إجراءات سريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="sendWhatsAppUpdate()">
                            <i class="fab fa-whatsapp me-1"></i>
                            إرسال تحديث واتساب
                        </button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="fas fa-sticky-note me-1"></i>
                            إضافة ملاحظة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">ملاحظات</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="4">{{ old('notes', $carImport->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('carimport.imports.show', $carImport) }}" class="btn btn-secondary">إلغاء</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            حفظ التغييرات
        </button>
    </div>
</form>

@push('scripts')
<script>
function sendWhatsAppUpdate() {
    @if($carImport->recipient_phone)
    const phone = '{{ $carImport->recipient_phone }}';
    const message = `تحديث حالة السيارة رقم اللوت: {{ $carImport->lot_number }}\nالحالة الحالية: {{ $carImport->shipping_status }}\nشركة الميم لاستيراد السيارات`;
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
    @else
    alert('لا يوجد رقم هاتف للمستلم');
    @endif
}

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
