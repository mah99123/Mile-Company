@extends('layouts.app')

@section('content')
<div class="container-fluid rtl">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">سجل النشاط</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">سجل النشاط الكامل</h5>
                        <a href="{{ route('security.index') }}" class="btn btn-sm btn-primary">العودة إلى إعدادات الأمان</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النشاط</th>
                                    <th>عنوان IP</th>
                                    <th>المتصفح</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityLog as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ \Str::limit($activity->user_agent, 30) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $activityLog->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
