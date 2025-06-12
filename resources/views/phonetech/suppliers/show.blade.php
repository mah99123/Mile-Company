@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-truck me-2"></i>
        تفاصيل المورد: {{ $supplier->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('phonetech.suppliers.edit', $supplier) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل المورد
        </a>
        <a href="{{ route('phonetech.suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Supplier Information -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($supplier->name, 0, 1) }}
                </div>
                <h4>{{ $supplier->name }}</h4>
                <p class="text-muted">{{ $supplier->company_name ?? 'مورد فردي' }}</p>
                <div class="d-flex justify-content-center gap-2">
                    @if($supplier->status == 'active')
                        <span class="badge bg-success fs-6">نشط</span>
                    @else
                        <span class="badge bg-secondary fs-6">غير نشط</span>
                    @endif
                    <span class="badge bg-info fs-6">{{ $supplier->country }}</span>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الاتصال</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">رقم الهاتف</label>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                    </p>
                </div>
                @if($supplier->email)
                <div class="mb-3">
                    <label class="form-label text-muted">البريد الإلكتروني</label>
                    <p class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                    </p>
                </div>
                @endif
                @if($supplier->address)
                <div class="mb-3">
                    <label class="form-label text-muted">العنوان</label>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $supplier->address }}
                    </p>
                </div>
                @endif
                <div class="mb-0">
                    <label class="form-label text-muted">تاريخ التسجيل</label>
                    <p class="mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        {{ $supplier->created_at->format('Y-m-d') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('phonetech.suppliers.edit', $supplier) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل المورد
                    </a>
                    <a href="tel:{{ $supplier->phone }}" class="btn btn-success">
                        <i class="fas fa-phone me-1"></i>
                        اتصال هاتفي
                    </a>
                    @if($supplier->email)
                    <a href="mailto:{{ $supplier->email }}" class="btn btn-info">
                        <i class="fas fa-envelope me-1"></i>
                        إرسال بريد إلكتروني
                    </a>
                    @endif
                    <button type="button" class="btn btn-warning" onclick="sendWhatsAppMessage()">
                        <i class="fab fa-whatsapp me-1"></i>
                        رسالة واتساب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات المورد</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h4 class="text-primary">{{ $supplier->products->count() }}</h4>
                        <small class="text-muted">المنتجات المتوفرة</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-success">{{ $supplier->purchaseOrders->count() }}</h4>
                        <small class="text-muted">أوامر الشراء</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-info">{{ number_format($supplier->purchaseOrders->sum('total_amount'), 0) }}</h4>
                        <small class="text-muted">إجمالي المشتريات (دينار)</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4 class="text-warning">{{ $supplier->purchaseOrders->where('status', 'pending')->count() }}</h4>
                        <small class="text-muted">أوامر معلقة</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">المنتجات المتوفرة</h5>
                <a href="{{ route('phonetech.products.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    إضافة منتج
                </a>
            </div>
            <div class="card-body">
                @if($supplier->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>الفئة</th>
                                    <th>سعر التكلفة</th>
                                    <th>سعر البيع</th>
                                    <th>الكمية</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->products->take(10) as $product)
                                <tr>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ number_format($product->cost_price, 0) }} دينار</td>
                                    <td>{{ number_format($product->selling_price, 0) }} دينار</td>
                                    <td>
                                        <span class="badge {{ $product->quantity_in_stock > $product->minimum_stock_level ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->quantity_in_stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('phonetech.products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($supplier->products->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('phonetech.products.index', ['supplier_id' => $supplier->id]) }}" class="btn btn-outline-primary">
                                عرض جميع المنتجات ({{ $supplier->products->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box fa-3x mb-3"></i>
                        <p>لا توجد منتجات مسجلة لهذا المورد</p>
                        <a href="{{ route('phonetech.products.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة منتج جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Purchase Orders -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">أوامر الشراء الأخيرة</h5>
                <a href="{{ route('phonetech.purchase-orders.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>
                    أمر شراء جديد
                </a>
            </div>
            <div class="card-body">
                @if($supplier->purchaseOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الأمر</th>
                                    <th>التاريخ</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->purchaseOrders->latest()->take(5) as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->order_date->format('Y-m-d') }}</td>
                                    <td>{{ number_format($order->total_amount, 0) }} دينار</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">معلق</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-primary">مؤكد</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success">تم التسليم</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">ملغي</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('phonetech.purchase-orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>لا توجد أوامر شراء مسجلة</p>
                        <a href="{{ route('phonetech.purchase-orders.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            إنشاء أمر شراء جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($supplier->notes)
<!-- Notes -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ملاحظات</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $supplier->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function sendWhatsAppMessage() {
    const phone = '{{ $supplier->phone }}';
    const message = `مرحباً {{ $supplier->name }}\nنتواصل معكم من شركة محمد فون تك`;
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
</script>
@endpush

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endsection
