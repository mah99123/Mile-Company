@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        إضافة منتج جديد
    </h1>
    <a href="{{ route('phonetech.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-1"></i>
        العودة للقائمة
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('phonetech.products.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">الفئة <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">اختر الفئة</option>
                            <option value="Phone" {{ old('category') == 'Phone' ? 'selected' : '' }}>هاتف</option>
                            <option value="Accessory" {{ old('category') == 'Accessory' ? 'selected' : '' }}>إكسسوار</option>
                            <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>إلكترونيات</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sku" class="form-label">رقم SKU <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                               id="sku" name="sku" value="{{ old('sku') }}" required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">المورد <span class="text-danger">*</span></label>
                        <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                            <option value="">اختر المورد</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="cost_price" class="form-label">سعر التكلفة (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                               id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required>
                        @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="selling_price" class="form-label">سعر البيع (دينار) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" 
                               id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="quantity_in_stock" class="form-label">الكمية في المخزون <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity_in_stock') is-invalid @enderror" 
                               id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock') }}" required>
                        @error('quantity_in_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="reorder_threshold" class="form-label">حد إعادة الطلب <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('reorder_threshold') is-invalid @enderror" 
                               id="reorder_threshold" name="reorder_threshold" value="{{ old('reorder_threshold') }}" required>
                        @error('reorder_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('phonetech.products.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ المنتج
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
