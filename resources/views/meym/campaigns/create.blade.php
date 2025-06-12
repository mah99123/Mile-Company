@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إضافة حملة جديدة
    </h1>
    <a href="{{ route('meym.campaigns.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للقائمة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('meym.campaigns.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="page_name" class="form-label">اسم الصفحة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('page_name') is-invalid @enderror" 
                               id="page_name" name="page_name" value="{{ old('page_name') }}" required>
                        @error('page_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="owner_name" class="form-label">اسم المالك <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                               id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                        @error('owner_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialization" class="form-label">التخصص <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                               id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="budget_total" class="form-label">إجمالي الميزانية (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('budget_total') is-invalid @enderror" 
                               id="budget_total" name="budget_total" value="{{ old('budget_total') }}" required>
                        @error('budget_total')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">تاريخ النهاية <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="launch_date" class="form-label">تاريخ الإطلاق <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('launch_date') is-invalid @enderror" 
                               id="launch_date" name="launch_date" value="{{ old('launch_date') }}" required>
                        @error('launch_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="stop_date" class="form-label">تاريخ التوقف</label>
                        <input type="date" class="form-control @error('stop_date') is-invalid @enderror" 
                               id="stop_date" name="stop_date" value="{{ old('stop_date') }}">
                        @error('stop_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('meym.campaigns.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ الحملة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
