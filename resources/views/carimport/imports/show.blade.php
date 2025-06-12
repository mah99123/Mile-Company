@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-car me-2"></i>
        تفاصيل عملية الاستيراد: {{ $carImport->lot_number }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('carimport.imports.edit', $carImport) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل العملية
        </a>
        <button type="button" class="btn btn-success me-2" onclick="printDetails()">
            <i class="fas fa-print me-1"></i>
            طباعة التفاصيل
        </button>
        <a href="{{ route('carimport.imports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Import Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل المزاد والاستيراد</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>نوع المزاد:</strong></td>
                                <td><span class="badge bg-info">{{ $carImport->auction_type }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>رقم اللوت:</strong></td>
                                <td><code>{{ $carImport->lot_number }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>رقم فاتورة المزاد:</strong></td>
                                <td>{{ $carImport->auction_invoice_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ فاتورة المزاد:</strong></td>
                                <td>{{ $carImport->auction_invoice_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td><strong>رقم عقد المكتب:</strong></td>
                                <td>{{ $carImport->office_contract_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ عقد المكتب:</strong></td>
                                <td>{{ $carImport->office_contract_date->format('Y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>شركة المشتري:</strong></td>
                                <td>{{ $carImport->buyer_company }}</td>
                            </tr>
                            <tr>
                                <td><strong>شركة الشحن:</strong></td>
                                <td>{{ $carImport->shipping_company }}</td>
                            </tr>
                            <tr>
                                <td><strong>الموظف المسؤول:</strong></td>
                                <td>{{ $carImport->employee->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>العملة:</strong></td>
                                <td>{{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>رقم الحاوية:</strong></td>
                                <td>{{ $carImport->container_number ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong>اسم المستلم:</strong></td>
                                <td>{{ $carImport->recipient_name ?? 'غير محدد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">التفاصيل المالية</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>المبلغ الإجمالي مع النقل:</strong></td>
                                <td class="text-primary">{{ number_format($carImport->total_with_transfer, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>المبلغ المستلم:</strong></td>
                                <td class="text-success">{{ number_format($carImport->amount_received, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>المبلغ المتبقي:</strong></td>
                                <td class="{{ $carImport->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($carImport->remaining_amount, 2) }} {{ $carImport->currency }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>مبلغ فاتورة المكتب:</strong></td>
                                <td>{{ number_format($carImport->office_invoice_amount, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>تكلفة الشحن للشركة:</strong></td>
                                <td>{{ number_format($carImport->company_shipping_cost, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>تكلفة الشحن للعميل:</strong></td>
                                <td>{{ number_format($carImport->customer_shipping_cost, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>ربح الشحن:</strong></td>
                                <td class="text-success">{{ number_format($carImport->shipping_profit, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                            <tr>
                                <td><strong>عمولة المكتب:</strong></td>
                                <td class="text-success">{{ number_format($carImport->office_commission, 2) }} {{ $carImport->currency }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>إجمالي الربح:</strong> 
                            <span class="fs-5 text-success">{{ number_format($carImport->total_profit, 2) }} {{ $carImport->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">مراحل الشحن والتسليم</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $carImport->pull_date ? 'completed' : 'pending' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>سحب السيارة من المزاد</h6>
                            <p class="text-muted">{{ $carImport->pull_date ? $carImport->pull_date->format('Y-m-d') : 'لم يتم بعد' }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $carImport->shipping_date ? 'completed' : 'pending' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>بداية الشحن</h6>
                            <p class="text-muted">{{ $carImport->shipping_date ? $carImport->shipping_date->format('Y-m-d') : 'لم يتم بعد' }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $carImport->arrival_date ? 'completed' : 'pending' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>وصول السيارة</h6>
                            <p class="text-muted">{{ $carImport->arrival_date ? $carImport->arrival_date->format('Y-m-d') : 'لم تصل بعد' }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $carImport->recipient_receive_date ? 'completed' : 'pending' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>تسليم السيارة للعميل</h6>
                            <p class="text-muted">{{ $carImport->recipient_receive_date ? $carImport->recipient_receive_date->format('Y-m-d') : 'لم يتم التسليم بعد' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">حالة الشحن</h5>
            </div>
            <div class="card-body text-center">
                @switch($carImport->shipping_status)
                    @case('Pending')
                        <span class="badge bg-warning fs-6 p-3">في الانتظار</span>
                        @break
                    @case('Shipped')
                        <span class="badge bg-primary fs-6 p-3">قيد الشحن</span>
                        @break
                    @case('Arrived')
                        <span class="badge bg-info fs-6 p-3">وصل</span>
                        @break
                    @case('Delivered')
                        <span class="badge bg-success fs-6 p-3">تم التسليم</span>
                        @break
                @endswitch
                
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        <i class="fas fa-edit me-1"></i>
                        تحديث الحالة
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('carimport.imports.edit', $carImport) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل العملية
                    </a>
                    <button type="button" class="btn btn-success" onclick="sendWhatsAppUpdate()">
                        <i class="fab fa-whatsapp me-1"></i>
                        إرسال تحديث واتساب
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                        <i class="fas fa-sticky-note me-1"></i>
                        إضافة ملاحظة
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="printDetails()">
                        <i class="fas fa-print me-1"></i>
                        طباعة التفاصيل
                    </button>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        @if($carImport->recipient_name)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات المستلم</h5>
            </div>
            <div class="card-body">
                <h6>{{ $carImport->recipient_name }}</h6>
                <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $carImport->recipient_phone }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث حالة الشحن</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('carimport.imports.update-status', $carImport) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الحالة الحالية</label>
                        <input type="text" class="form-control" value="{{ $carImport->shipping_status }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة الجديدة <span class="text-danger">*</span></label>
                        <select class="form-select" name="shipping_status" required>
                            <option value="Pending" {{ $carImport->shipping_status == 'Pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="Shipped" {{ $carImport->shipping_status == 'Shipped' ? 'selected' : '' }}>قيد الشحن</option>
                            <option value="Arrived" {{ $carImport->shipping_status == 'Arrived' ? 'selected' : '' }}>وصل</option>
                            <option value="Delivered" {{ $carImport->shipping_status == 'Delivered' ? 'selected' : '' }}>تم التسليم</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ التحديث</label>
                        <input type="date" class="form-control" name="update_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث الحالة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-item.pending .timeline-marker {
    background: #ffc107;
    box-shadow: 0 0 0 2px #ffc107;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-item.completed .timeline-content h6 {
    color: #28a745;
}

.timeline-item.pending .timeline-content h6 {
    color: #6c757d;
}
</style>

@push('scripts')
<script>
function printDetails() {
    window.print();
}

function sendWhatsAppUpdate() {
    @if($carImport->recipient_phone)
    const phone = '{{ $carImport->recipient_phone }}';
    const message = `تحديث حالة السيارة رقم اللوت: {{ $carImport->lot_number }}\nالحالة الحالية: {{ $carImport->shipping_status }}\nشركة الميم لاستيراد السيارات`;
    const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
    @else
    alert('لا يوجد رقم هاتف للمستلم');
    @endif
}
</script>
@endpush
@endsection
