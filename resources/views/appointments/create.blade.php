@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-plus"></i> إنشاء موعد جديد</h2>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.appointments.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">عنوان الموعد <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">المسؤول <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">اختر المسؤول</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">تاريخ ووقت الموعد <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">المكان</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">الأولوية <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reminder_minutes" class="form-label">تذكير قبل (دقيقة) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('reminder_minutes') is-invalid @enderror" id="reminder_minutes" name="reminder_minutes" required>
                                        <option value="5">5 دقائق</option>
                                        <option value="15">15 دقيقة</option>
                                        <option value="30" selected>30 دقيقة</option>
                                        <option value="60">ساعة واحدة</option>
                                        <option value="120">ساعتان</option>
                                        <option value="1440">يوم واحد</option>
                                    </select>
                                    @error('reminder_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reminder_type" class="form-label">نوع التذكير <span class="text-danger">*</span></label>
                                    <select class="form-select @error('reminder_type') is-invalid @enderror" id="reminder_type" name="reminder_type" required>
                                        <option value="notification" {{ old('reminder_type', 'notification') == 'notification' ? 'selected' : '' }}>إشعار</option>
                                        <option value="email" {{ old('reminder_type') == 'email' ? 'selected' : '' }}>إيميل</option>
                                        <option value="whatsapp" {{ old('reminder_type') == 'whatsapp' ? 'selected' : '' }}>واتساب</option>
                                    </select>
                                    @error('reminder_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="attendees" class="form-label">الحضور</label>
                            <input type="text" class="form-control @error('attendees') is-invalid @enderror" 
                                   id="attendees" name="attendees" value="{{ old('attendees') }}" 
                                   placeholder="أسماء الحضور مفصولة بفواصل">
                            @error('attendees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف الموعد</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ الموعد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
