@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        تعديل المورد: {{ $supplier->name }}
    </h1>
    <a href="{{ route('phonetech.suppliers.show', $supplier) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للمورد
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('phonetech.suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المورد <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">اسم الشركة</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name) }}">
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $supplier->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="country" class="form-label">البلد <span class="text-danger">*</span></label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                            <option value="">اختر البلد</option>
                            <option value="العراق" {{ old('country', $supplier->country) == 'العراق' ? 'selected' : '' }}>العراق</option>
                            <option value="الصين" {{ old('country', $supplier->country) == 'الصين' ? 'selected' : '' }}>الصين</option>
                            <option value="تركيا" {{ old('country', $supplier->country) == 'تركيا' ? 'selected' : '' }}>تركيا</option>
                            <option value="الإمارات" {{ old('country', $supplier->country) == 'الإمارات' ? 'selected' : '' }}>الإمارات</option>
                            <option value="الأردن" {{ old('country', $supplier->country) == 'الأردن' ? 'selected' : '' }}>الأردن</option>
                            <option value="لبنان" {{ old('country', $supplier->country) == 'لبنان' ? 'selected' : '' }}>لبنان</option>
                            <option value="أخرى" {{ old('country', $supplier->country) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">العنوان</label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('phonetech.suppliers.show', $supplier) }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
