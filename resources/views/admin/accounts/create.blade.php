@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إنشاء حساب جديد
    </h1>
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للحسابات
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.accounts.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_name" class="form-label">اسم الحساب <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                               id="account_name" name="account_name" value="{{ old('account_name') }}" required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_type" class="form-label">نوع الحساب <span class="text-danger">*</span></label>
                        <select class="form-select @error('account_type') is-invalid @enderror" 
                                id="account_type" name="account_type" required>
                            <option value="">اختر نوع الحساب</option>
                            <option value="Asset" {{ old('account_type') == 'Asset' ? 'selected' : '' }}>الأصول</option>
                            <option value="Liability" {{ old('account_type') == 'Liability' ? 'selected' : '' }}>الخصوم</option>
                            <option value="Equity" {{ old('account_type') == 'Equity' ? 'selected' : '' }}>حقوق الملكية</option>
                            <option value="Revenue" {{ old('account_type') == 'Revenue' ? 'selected' : '' }}>الإيرادات</option>
                            <option value="Expense" {{ old('account_type') == 'Expense' ? 'selected' : '' }}>المصروفات</option>
                        </select>
                        @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="platform" class="form-label">المنصة <span class="text-danger">*</span></label>
                        <select class="form-select @error('platform') is-invalid @enderror" 
                                id="platform" name="platform" required>
                            <option value="">اختر المنصة</option>
                            <option value="Meym" {{ old('platform') == 'Meym' ? 'selected' : '' }}>ميم للحملات الإعلانية</option>
                            <option value="PhoneTech" {{ old('platform') == 'PhoneTech' ? 'selected' : '' }}>محمد فون تك</option>
                            <option value="CarImport" {{ old('platform') == 'CarImport' ? 'selected' : '' }}>استيراد السيارات</option>
                            <option value="General" {{ old('platform') == 'General' ? 'selected' : '' }}>عام</option>
                        </select>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ الحساب
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
