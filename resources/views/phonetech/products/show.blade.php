@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-mobile-alt me-2"></i>
        تفاصيل المنتج: {{ $product->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.products.edit', $product) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل المنتج
        </a>
        <a href="{{ route('phonetech.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Product Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات المنتج</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>اسم المنتج:</strong></td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>الفئة:</strong></td>
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
                            </tr>
                            <tr>
                                <td><strong>رقم SKU:</strong></td>
                                <td><code>{{ $product->sku }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>المورد:</strong></td>
                                <td>{{ $product->supplier->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>الحالة:</strong></td>
                                <td>
                                    @if($product->status == 'active')
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>سعر التكلفة:</strong></td>
                                <td>{{ number_format($product->cost_price, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>سعر البيع:</strong></td>
                                <td>{{ number_format($product->selling_price, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>هامش الربح:</strong></td>
                                <td class="text-success">{{ number_format($product->profit_margin, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>الكمية المتوفرة:</strong></td>
                                <td>
                                    <span class="badge {{ $product->is_low_stock ? 'bg-danger' : 'bg-success' }}">
                                        {{ $product->quantity_in_stock }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>حد إعادة الطلب:</strong></td>
                                <td>{{ $product->reorder_threshold }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mt-3">
                    <strong>الوصف:</strong>
                    <p class="mt-2">{{ $product->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sales History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">تاريخ المبيعات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>العميل</th>
                                <th>تاريخ البيع</th>
                                <th>المبلغ الإجمالي</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_id }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($sale->total_amount, 2) }} دينار</td>
                                <td>
                                    @switch($sale->status)
                                        @case('Pending')
                                            <span class="badge bg-warning">في الانتظار</span>
                                            @break
                                        @case('Active')
                                            <span class="badge bg-primary">نشط</span>
                                            @break
                                        @case('Completed')
                                            <span class="badge bg-success">مكتمل</span>
                                            @break
                                        @case('Overdue')
                                            <span class="badge bg-danger">متأخر</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('phonetech.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">لا توجد مبيعات لهذا المنتج</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Stock Alert -->
        @if($product->is_low_stock)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>تنبيه!</strong> المخزون منخفض. الكمية الحالية: {{ $product->quantity_in_stock }}
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('phonetech.sales.create') }}?product={{ $product->product_id }}" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i>
                        بيع هذا المنتج
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStockModal">
                        <i class="fas fa-boxes me-1"></i>
                        تحديث المخزون
                    </button>
                    <a href="{{ route('phonetech.products.edit', $product) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل المنتج
                    </a>
                </div>
            </div>
        </div>

        <!-- Supplier Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات المورد</h5>
            </div>
            <div class="card-body">
                <h6>{{ $product->supplier->name }}</h6>
                <p class="text-muted mb-2">{{ $product->supplier->contact_person }}</p>
                <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $product->supplier->phone }}</p>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $product->supplier->email }}</p>
                <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $product->supplier->address }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث المخزون</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('phonetech.products.update-stock', $product) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الكمية الحالية</label>
                        <input type="number" class="form-control" value="{{ $product->quantity_in_stock }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نوع التحديث</label>
                        <select class="form-select" name="update_type" required>
                            <option value="">اختر نوع التحديث</option>
                            <option value="add">إضافة كمية</option>
                            <option value="subtract">خصم كمية</option>
                            <option value="set">تحديد كمية جديدة</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الكمية</label>
                        <input type="number" class="form-control" name="quantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث المخزون</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
