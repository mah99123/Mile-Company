@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-mobile-alt me-2"></i>
        إدارة المنتجات - محمد فون تك
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة منتج جديد
        </a>
        <a  style="margin-right: 5px;" href="{{ route('phonetech.suppliers.index') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
           ادارة الموردين
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('phonetech.products.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم المنتج أو رقم SKU">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الفئة</label>
                    <select class="form-select" name="category">
                        <option value="">جميع الفئات</option>
                        <option value="Phone" {{ request('category') == 'Phone' ? 'selected' : '' }}>هواتف</option>
                        <option value="Accessory" {{ request('category') == 'Accessory' ? 'selected' : '' }}>إكسسوارات</option>
                        <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>إلكترونيات</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">المخزون المنخفض</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="form-check-label">عرض المنتجات منخفضة المخزون فقط</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary d-block w-100">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>SKU</th>
                        <th>سعر التكلفة</th>
                        <th>سعر البيع</th>
                        <th>المخزون</th>
                        <th>المورد</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="{{ $product->is_low_stock ? 'table-warning' : '' }}">
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->is_low_stock)
                                <span class="badge bg-warning ms-2">مخزون منخفض</span>
                            @endif
                        </td>
                        <td>
                            @switch($product->category)
                                @case('Phone')
                                    <span class="badge bg-primary">هاتف</span>
                                    @break
                                @case('Accessory')
                                    <span class="badge bg-info">إكسسوار</span>
                                    @break
                                @case('Electronics')
                                    <span class="badge bg-success">إلكترونيات</span>
                                    @break
                            @endswitch
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>{{ number_format($product->cost_price, 2) }} دينار</td>
                        <td>{{ number_format($product->selling_price, 2) }} دينار</td>
                        <td>
                            <span class="badge {{ $product->is_low_stock ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->quantity_in_stock }}
                            </span>
                        </td>
                        <td>{{ $product->supplier->name }}</td>
                        <td>
                            @if($product->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('phonetech.products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('phonetech.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('phonetech.products.destroy', $product) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>لا توجد منتجات مطابقة للبحث</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $products->links() }}
    </div>
</div>
@endsection
