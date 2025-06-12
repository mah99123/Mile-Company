@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إضافة دور جديد
    </h1>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للقائمة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الدور <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف الدور</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description" value="{{ old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">الصلاحيات <span class="text-danger">*</span></label>
                <div class="row">
                    @foreach($permissions as $module => $modulePermissions)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input module-checkbox" type="checkbox" 
                                           id="module_{{ $loop->index }}" data-module="{{ $module }}">
                                    <label class="form-check-label fw-bold" for="module_{{ $loop->index }}">
                                        {{ $module }}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($modulePermissions as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                           name="permissions[]" value="{{ $permission->id }}" 
                                           id="permission_{{ $permission->id }}" 
                                           data-module="{{ $module }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    إنشاء الدور
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle module checkbox clicks
    document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
        moduleCheckbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            
            permissionCheckboxes.forEach(function(checkbox) {
                checkbox.checked = moduleCheckbox.checked;
            });
        });
    });
    
    // Handle individual permission checkbox clicks
    document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
        permissionCheckbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
            const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const checkedPermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
            
            if (checkedPermissions.length === permissionCheckboxes.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else if (checkedPermissions.length > 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            }
        });
    });
});
</script>
@endpush
@endsection
