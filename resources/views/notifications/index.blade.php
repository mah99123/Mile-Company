@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Notifications') }}</h5>
                    <div>
                        <button class="btn btn-sm btn-primary" id="markAllAsRead">{{ __('Mark All as Read') }}</button>
                        <button class="btn btn-sm btn-danger" id="deleteAll">{{ __('Delete All') }}</button>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="notificationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">{{ __('All') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="false">{{ __('Unread') }}</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="notificationTabsContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="list-group">
                                @forelse(auth()->user()->notifications as $notification)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                            <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            @if(!$notification->read_at)
                                                <button class="btn btn-sm btn-outline-primary mark-as-read" data-id="{{ $notification->id }}">{{ __('Mark as Read') }}</button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger delete-notification" data-id="{{ $notification->id }}">{{ __('Delete') }}</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info">{{ __('No notifications found.') }}</div>
                                @endforelse
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="unread" role="tabpanel" aria-labelledby="unread-tab">
                            <div class="list-group">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center list-group-item-primary">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                            <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary mark-as-read" data-id="{{ $notification->id }}">{{ __('Mark as Read') }}</button>
                                            <button class="btn btn-sm btn-outline-danger delete-notification" data-id="{{ $notification->id }}">{{ __('Delete') }}</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info">{{ __('No unread notifications.') }}</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark as read
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/notifications/${id}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            });
        });

        // Delete notification
        document.querySelectorAll('.delete-notification').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            });
        });

        // Mark all as read
        document.getElementById('markAllAsRead').addEventListener('click', function() {
            fetch('/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        });

        // Delete all
        document.getElementById('deleteAll').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete all notifications?')) {
                fetch('/notifications/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
@endsection
