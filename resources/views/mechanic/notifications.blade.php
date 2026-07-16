@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link">
        <i class="fas fa-bell"></i> Service Requests
    </a>
    <a href="{{ route('mechanic.my-jobs') }}" class="nav-link">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="{{ route('mechanic.earnings') }}" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link">
        <i class="fas fa-star"></i> Reviews
    </a>
    <a href="{{ route('mechanic.profile') }}" class="nav-link">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('mechanic.notifications') }}" class="nav-link active">
        <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('mechanic.settings') }}" class="nav-link">
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
            {{--<i class="fas fa-bell me-2 text-primary"></i> Notifications--}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
            Stay updated on requests and activities.
        </p>
    </div>
    @if(auth()->user()->unreadNotifications->count())
    <form method="POST"
          action="{{ route('mechanic.notifications.mark-all-read') }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-outline-primary"
                style="padding:10px 20px">
            <i class="fas fa-check-double me-2"></i> Mark All Read
        </button>
    </form>
    @endif
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-bell"></i></div>
            <div class="stat-info">
                <h3>{{ auth()->user()->notifications->count() }}</h3>
                <p>Total</p>
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

<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($notifications->count())
            @foreach($notifications as $notification)
            <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                <div class="d-flex align-items-start gap-3">
                    <div class="notification-icon
                         {{ is_null($notification->read_at) ? 'unread-icon' : 'read-icon' }}">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="flex-1">
                        <div class="d-flex justify-content-between">
                            <p style="font-weight:{{ is_null($notification->read_at) ? '700':'500' }};
                                      color:#1a3c6e;margin:0;font-size:14px">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <span style="font-size:11px;color:#9ca3af">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p style="color:#6b7280;font-size:13px;margin:4px 0 0">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="p-3 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                <h5 style="color:#1a3c6e;font-weight:700">No Notifications</h5>
                <p class="text-muted">You're all caught up!</p>
            </div>
        @endif
    </div>
</div>

@endsection