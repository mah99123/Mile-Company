@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bullhorn me-2"></i>
        تفاصيل الحملة: {{ $campaign->page_name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('meym.campaigns.edit', $campaign) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i>
            تعديل الحملة
        </a>
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
            <i class="fas fa-plus me-1"></i>
            إضافة تحديث
        </button>
        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#whatsappModal">
            <i class="fab fa-whatsapp me-1"></i>
            مراسلة واتساب
        </button>
        <a href="{{ route('meym.campaigns.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Campaign Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل الحملة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>اسم الصفحة:</strong></td>
                                <td>{{ $campaign->page_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>اسم المالك:</strong></td>
                                <td>{{ $campaign->owner_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>التخصص:</strong></td>
                                <td><span class="badge bg-info">{{ $campaign->specialization }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>منشئ الحملة:</strong></td>
                                <td>{{ $campaign->creator->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>إجمالي الميزانية:</strong></td>
                                <td class="text-primary">{{ number_format($campaign->budget_total, 2) }} دينار</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ البداية:</strong></td>
                                <td>{{ $campaign->start_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ النهاية:</strong></td>
                                <td>{{ $campaign->end_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ الإطلاق:</strong></td>
                                <td>{{ $campaign->launch_date->format('Y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($campaign->notes)
                <div class="mt-3">
                    <strong>ملاحظات:</strong>
                    <p class="mt-2">{{ $campaign->notes }}</p>
                </div>
                @endif
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>تقدم الحملة</span>
                        <span>{{ $campaign->progress_percentage }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $campaign->progress_percentage }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $campaign->days_remaining }} يوم متبقي من أصل {{ $campaign->total_days }} يوم
                    </small>
                </div>
            </div>
        </div>

        <!-- Campaign Updates -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">تحديثات الحملة</h5>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
                    <i class="fas fa-plus me-1"></i>
                    إضافة تحديث
                </button>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($campaign->updates->sortByDesc('update_date') as $update)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">تحديث الحملة</h6>
                                <small class="text-muted">{{ $update->update_date->format('Y-m-d H:i') }}</small>
                            </div>
                            <p class="mb-0">{{ $update->update_text }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>لا توجد تحديثات للحملة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- WhatsApp Messages -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">مراسلات الواتساب</h5>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                    <i class="fab fa-whatsapp me-1"></i>
                    إضافة رسالة
                </button>
            </div>
            <div class="card-body">
                <div class="chat-container">
                    @forelse($campaign->whatsappThreads->sortBy('message_date') as $message)
                    <div class="chat-message {{ $message->message_type == 'sent' ? 'sent' : 'received' }}">
                        <div class="message-content">
                            <p>{{ $message->message_content }}</p>
                            <small class="message-time">
                                {{ $message->message_date->format('Y-m-d H:i') }}
                                @if($message->message_type == 'sent')
                                    <i class="fas fa-check-double text-primary"></i>
                                @endif
                            </small>
                        </div>
                        <div class="message-info">
                            <strong>{{ $message->customer_whatsapp }}</strong>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fab fa-whatsapp fa-3x mb-3"></i>
                        <p>لا توجد مراسلات واتساب</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Campaign Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">حالة الحملة</h5>
            </div>
            <div class="card-body text-center">
                @switch($campaign->status)
                    @case('active')
                        <span class="badge bg-success fs-6 p-3">نشطة</span>
                        @break
                    @case('paused')
                        <span class="badge bg-warning fs-6 p-3">متوقفة</span>
                        @break
                    @case('completed')
                        <span class="badge bg-primary fs-6 p-3">مكتملة</span>
                        @break
                    @case('cancelled')
                        <span class="badge bg-danger fs-6 p-3">ملغية</span>
                        @break
                @endswitch
                
                @if($campaign->is_ending_soon)
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تنبيه!</strong> الحملة تنتهي قريباً
                </div>
                @endif
            </div>
        </div>

        <!-- Campaign Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات الحملة</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $campaign->updates->count() }}</h4>
                        <small class="text-muted">التحديثات</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success">{{ $campaign->whatsappThreads->count() }}</h4>
                        <small class="text-muted">الرسائل</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $campaign->days_running }}</h4>
                        <small class="text-muted">أيام التشغيل</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $campaign->days_remaining }}</h4>
                        <small class="text-muted">أيام متبقية</small>
                    </div>
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
                    <a href="{{ route('meym.campaigns.edit', $campaign) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        تعديل الحملة
                    </a>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
                        <i class="fas fa-plus me-1"></i>
                        إضافة تحديث
                    </button>
                    <button type="button" class="btn btn-info" onclick="generateReport()">
                        <i class="fas fa-chart-bar me-1"></i>
                        تقرير الحملة
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="printCampaign()">
                        <i class="fas fa-print me-1"></i>
                        طباعة التفاصيل
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Update Modal -->
<div class="modal fade" id="addUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة تحديث للحملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('meym.campaigns.add-update', $campaign) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">نص التحديث <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="update_text" rows="4" required placeholder="اكتب تحديث الحملة هنا..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إضافة التحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- WhatsApp Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة رسالة واتساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('meym.campaigns.add-whatsapp', $campaign) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">رقم الواتساب <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="customer_whatsapp" required placeholder="+964xxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نوع الرسالة <span class="text-danger">*</span></label>
                        <select class="form-select" name="message_type" required>
                            <option value="sent">مرسلة</option>
                            <option value="received">مستلمة</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">محتوى الرسالة <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message_content" rows="4" required placeholder="اكتب محتوى الرسالة هنا..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إضافة الرسالة</button>
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
    background: #28a745;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #28a745;
}

.chat-container {
    max-height: 400px;
    overflow-y: auto;
}

.chat-message {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-start;
}

.chat-message.sent {
    justify-content: flex-end;
}

.chat-message.sent .message-content {
    background: #007bff;
    color: white;
    margin-left: 50px;
}

.chat-message.received .message-content {
    background: #f8f9fa;
    color: #333;
    margin-right: 50px;
}

.message-content {
    padding: 10px 15px;
    border-radius: 18px;
    max-width: 70%;
    word-wrap: break-word;
}

.message-time {
    display: block;
    margin-top: 5px;
    opacity: 0.7;
}

.message-info {
    font-size: 12px;
    color: #6c757d;
    margin: 0 10px;
    align-self: flex-end;
}
</style>

@push('scripts')
<script>
function generateReport() {
    window.open('{{ route("meym.campaigns.report", $campaign) }}', '_blank');
}

function printCampaign() {
    window.print();
}
</script>
@endpush
@endsection
