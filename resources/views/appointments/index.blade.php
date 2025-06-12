@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-calendar-alt"></i> إدارة المواعيد</h2>
                <div>
                    <a href="{{ route('admin.appointments.calendar') }}" class="btn btn-info me-2">
                        <i class="fas fa-calendar"></i> عرض التقويم
                    </a>
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> موعد جديد
                    </a>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['total'] }}</h4>
                                    <p class="mb-0">إجمالي المواعيد</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['today'] }}</h4>
                                    <p class="mb-0">مواعيد اليوم</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-day fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['upcoming'] }}</h4>
                                    <p class="mb-0">مواعيد قادمة</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['overdue'] }}</h4>
                                    <p class="mb-0">مواعيد متأخرة</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- فلاتر البحث -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.appointments.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="date" class="form-label">التاريخ</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">جميع الحالات</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    <option value="postponed" {{ request('status') == 'postponed' ? 'selected' : '' }}>مؤجل</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="priority" class="form-label">الأولوية</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="">جميع الأولويات</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول المواعيد -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>التاريخ والوقت</th>
                                    <th>المسؤول</th>
                                    <th>الأولوية</th>
                                    <th>الحالة</th>
                                    <th>المكان</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                <tr>
                                    <td>
                                        <strong>{{ $appointment->title }}</strong>
                                        @if($appointment->description)
                                            <br><small class="text-muted">{{ Str::limit($appointment->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $appointment->appointment_date->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $appointment->appointment_date->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->priority_color }}">
                                            @switch($appointment->priority)
                                                @case('high') عالية @break
                                                @case('medium') متوسطة @break
                                                @case('low') منخفضة @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            @switch($appointment->status)
                                                @case('scheduled') مجدول @break
                                                @case('completed') مكتمل @break
                                                @case('cancelled') ملغي @break
                                                @case('postponed') مؤجل @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $appointment->location ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد مواعيد</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $appointments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
