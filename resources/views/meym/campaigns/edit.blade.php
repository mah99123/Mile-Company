@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        تعديل الحملة: {{ $campaign->page_name }}
    </h1>
    <a href="{{ route('meym.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للحملة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('meym.campaigns.update', $campaign) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="page_name" class="form-label">اسم الصفحة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('page_name') is-invalid @enderror" 
                               id="page_name" name="page_name" value="{{ old('page_name', $campaign->page_name) }}" required>
                        @error('page_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="owner_name" class="form-label">اسم المالك <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                               id="owner_name" name="owner_name" value="{{ old('owner_name', $campaign->owner_name) }}" required>
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
                               id="specialization" name="specialization" value="{{ old('specialization', $campaign->specialization) }}" required>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="budget_total" class="form-label">إجمالي الميزانية (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('budget_total') is-invalid @enderror" 
                               id="budget_total" name="budget_total" value="{{ old('budget_total', $campaign->budget_total) }}" required>
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
                               id="start_date" name="start_date" value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">تاريخ النهاية <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="launch_date" class="form-label">تاريخ الإطلاق <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('launch_date') is-invalid @enderror" 
                               id="launch_date" name="launch_date" value="{{ old('launch_date', $campaign->launch_date->format('Y-m-d')) }}" required>
                        @error('launch_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="stop_date" class="form-label">تاريخ التوقف</label>
                        <input type="date" class="form-control @error('stop_date') is-invalid @enderror" 
                               id="stop_date" name="stop_date" value="{{ old('stop_date', $campaign->stop_date?->format('Y-m-d')) }}">
                        @error('stop_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">حالة الحملة <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>نشطة</option>
                            <option value="paused" {{ old('status', $campaign->status) == 'paused' ? 'selected' : '' }}>متوقفة</option>
                            <option value="completed" {{ old('status', $campaign->status) == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            <option value="cancelled" {{ old('status', $campaign->status) == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="4">{{ old('notes', $campaign->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('meym.campaigns.show', $campaign) }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
