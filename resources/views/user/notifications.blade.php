@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('user.request-assistance') }}" class="nav-link">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="{{ route('user.my-requests') }}" class="nav-link">
        <i class="fas fa-list"></i> My Requests
    </a>
    <a href="{{ route('user.mechanics') }}" class="nav-link">
        <i class="fas fa-search"></i> Find Mechanics
    </a>
    <a href="{{ route('user.favourites') }}" class="nav-link">
        <i class="fas fa-heart"></i> Favourites
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-wallet"></i> Wallet
    </a>
    <a href="{{ route('user.notifications') }}" class="nav-link active">
        <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('user.profile') }}" class="nav-link">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('user.settings') }}" class="nav-link">
        <i class="fas fa-cog"></i> Settings
    </a>
    <a href="{{ route('logout') }}" class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            <i class="fas fa-bell me-2 text-primary"></i> Notifications
        </h4>
        <p class="text-muted mb-0" style="font-size:14px">
            Stay updated on your requests and activities.
        </p>
    </div>
    @if(auth()->user()->unreadNotifications->count())
    <form method="POST" action="{{ route('user.notifications.mark-all-read') }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-outline-primary"
                style="padding:10px 20px">
            <i class="fas fa-check-double me-2"></i> Mark All Read
        </button>
    </form>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Notification Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-bell"></i></div>
            <div class="stat-info">
                <h3>{{ auth()->user()->notifications->count() }}</h3>
                <p>Total Notifications</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-envelope"></i></div>
            <div class="stat-info">
                <h3>{{ auth()->user()->unreadNotifications->count() }}</h3>
                <p>Unread</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-envelope-open"></i></div>
            <div class="stat-info">
                <h3>{{ auth()->user()->readNotifications->count() }}</h3>
                <p>Read</p>
            </div>
        </div>
    </div>
</div>

{{-- Notifications List --}}
<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($notifications->count())
            @foreach($notifications as $notification)
            <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                <div class="d-flex align-items-start gap-3">
                    {{-- Icon --}}
                    <div class="notification-icon
                         {{ is_null($notification->read_at) ? 'unread-icon' : 'read-icon' }}">
                        @php
                            $type = $notification->data['type'] ?? 'general';
                            $icons = [
                                'request_accepted' => 'fas fa-check-circle',
                                'mechanic_arrived' => 'fas fa-map-marker-alt',
                                'request_completed' => 'fas fa-flag-checkered',
                                'request_cancelled' => 'fas fa-times-circle',
                                'new_message' => 'fas fa-comment',
                                'general' => 'fas fa-bell',
                            ];
                            $icon = $icons[$type] ?? 'fas fa-bell';
                        @endphp
                        <i class="{{ $icon }}"></i>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p style="font-weight:{{ is_null($notification->read_at) ? '700' : '500' }};
                                          color:#1a3c6e;margin:0;font-size:14px">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <p style="color:#6b7280;font-size:13px;margin:4px 0 0">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:11px;color:#9ca3af;white-space:nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(is_null($notification->read_at))
                                <form method="POST"
                                      action="{{ route('user.notifications.mark-read',
                                                       $notification->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-primary"
                                            style="padding:3px 10px;font-size:11px">
                                        Mark Read
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Pagination --}}
            <div class="p-3 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                <h5 style="color:#1a3c6e;font-weight:700">No Notifications</h5>
                <p class="text-muted">You're all caught up! No notifications yet.</p>
            </div>
        @endif
    </div>
</div>

@endsection

@push('styles')
<style>
.notification-item {
    padding: 16px 20px;
    border-bottom: 1px solid #f0f4ff;
    transition: background 0.2s ease;
}
.notification-item:last-child { border-bottom: none; }
.notification-item:hover { background: #f8fafc; }
.notification-item.unread { background: #eff6ff; }
.notification-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.unread-icon {
    background: linear-gradient(135deg, #1a3c6e, #3b82f6);
    color: white;
}
.read-icon {
    background: #f3f4f6;
    color: #6b7280;
}
</style>
@endpush