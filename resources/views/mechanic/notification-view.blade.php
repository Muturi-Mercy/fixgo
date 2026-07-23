@extends('layouts.app')

@section('title', 'Notification')
@section('page-title', 'Notification Detail')

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

{{-- <div class="mb-4">
    <a href="{{ route('mechanic.notifications') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Notifications
    </a>
</div> --}}

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="fixgo-card">
            @php
                $type = $notification->data['type'] ?? 'general';
                $headerColors = [
                    'announcement'      => 'linear-gradient(135deg,#f59e0b,#f97316)',
                    'new_message'       => 'linear-gradient(135deg,#3b82f6,#1a3c6e)',
                    'request_cancelled' => 'linear-gradient(135deg,#ef4444,#dc2626)',
                    'general'           => 'linear-gradient(135deg,#1a3c6e,#3b82f6)',
                ];
                $icons = [
                    'announcement'      => 'fas fa-bullhorn',
                    'new_message'       => 'fas fa-comment',
                    'request_cancelled' => 'fas fa-times-circle',
                    'general'           => 'fas fa-bell',
                ];
                $headerBg = $headerColors[$type] ?? $headerColors['general'];
                $icon = $icons[$type] ?? 'fas fa-bell';
            @endphp

            <div style="background:{{ $headerBg }};padding:32px 24px;
                        border-radius:14px 14px 0 0;text-align:center">
                <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);
                            border-radius:50%;margin:0 auto 16px;
                            display:flex;align-items:center;justify-content:center">
                    <i class="{{ $icon }}" style="font-size:28px;color:white"></i>
                </div>
                <h5 style="color:white;font-weight:700;margin:0">
                    {{ $notification->data['title'] ?? 'Notification' }}
                </h5>
                <p style="color:rgba(255,255,255,0.75);font-size:12px;margin:6px 0 0">
                    {{ $notification->created_at->format('M d, Y \a\t h:i A') }}
                </p>
            </div>

            <div class="fixgo-card-body">
                <div class="mb-4 text-center">
                    @if($type === 'announcement')
                        <span style="background:#fef3c7;color:#92400e;padding:5px 14px;
                                     border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-bullhorn me-1"></i> Platform Announcement
                        </span>
                    @elseif($type === 'new_message')
                        <span style="background:#eff6ff;color:#1a3c6e;padding:5px 14px;
                                     border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-comment me-1"></i> New Message
                        </span>
                    @elseif($type === 'request_cancelled')
                        <span style="background:#fee2e2;color:#991b1b;padding:5px 14px;
                                     border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-times-circle me-1"></i> Request Cancelled
                        </span>
                    @else
                        <span style="background:#f0f4ff;color:#1a3c6e;padding:5px 14px;
                                     border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-bell me-1"></i> General Notification
                        </span>
                    @endif
                </div>

                <div style="background:#f8fafc;border-radius:12px;padding:20px;
                            border-left:4px solid #3b82f6;margin-bottom:24px">
                    <p style="font-size:15px;color:#374151;line-height:1.8;margin:0">
                        {{ $notification->data['message'] ?? 'No message content.' }}
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center"
                     style="padding:12px 0;border-top:1px solid #f0f4ff">
                    <span style="font-size:12px;color:#9ca3af">
                        <i class="fas fa-clock me-1"></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                    <span style="font-size:12px;font-weight:600;
                                 color:{{ is_null($notification->read_at) ? '#3b82f6' : '#10b981' }}">
                        <i class="fas fa-{{ is_null($notification->read_at) ? 'envelope' : 'envelope-open' }} me-1"></i>
                        {{ is_null($notification->read_at) ? 'Unread' : 'Read' }}
                    </span>
                </div>

                <div class="mt-3">
                    @if(isset($notification->data['url']) && $notification->data['url'])
                        <a href="{{ $notification->data['url'] }}"
                           class="btn btn-fixgo w-100">
                            <i class="fas fa-arrow-right me-2"></i>
                            @if($type === 'new_message') Open Chat
                            @else View Details
                            @endif
                        </a>
                    @endif
                    <a href="{{ route('mechanic.notifications') }}"
                       class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection