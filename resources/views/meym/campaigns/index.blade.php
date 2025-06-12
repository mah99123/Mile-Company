@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bullhorn me-2"></i>
        إدارة الحملات - منصة ميم
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('meym.campaigns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إضافة حملة جديدة
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('meym.campaigns.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">البحث</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم الصفحة أو المالك">
                </div>
                <div class="col-md-2">
                    <label class="form-label">التخصص</label>
                    <select class="form-select" name="specialization">
                        <option value="">جميع التخصصات</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                {{ $spec }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>متوقفة</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary d-block w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Campaigns Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم الصفحة</th>
                        <th>المالك</th>
                        <th>التخصص</th>
                        <th>الميزانية</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <strong>{{ $campaign->page_name }}</strong>
                            @if($campaign->is_ending_soon)
                                <span class="badge bg-warning ms-2">تنتهي قريباً</span>
                            @endif
                        </td>
                        <td>{{ $campaign->owner_name }}</td>
                        <td>{{ $campaign->specialization }}</td>
                        <td>{{ number_format($campaign->budget_total, 2) }} دينار</td>
                        <td>{{ $campaign->start_date->format('Y-m-d') }}</td>
                        <td>{{ $campaign->end_date->format('Y-m-d') }}</td>
                        <td>
                            @switch($campaign->status)
                                @case('active')
                                    <span class="badge bg-success">نشطة</span>
                                    @break
                                @case('paused')
                                    <span class="badge bg-warning">متوقفة</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-primary">مكتملة</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">ملغية</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('meym.campaigns.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('meym.campaigns.edit', $campaign) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('meym.campaigns.destroy', $campaign) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الحملة؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>لا توجد حملات مطابقة للبحث</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
