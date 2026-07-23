@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

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
    <a href="{{ route('mechanic.notifications') }}" class="nav-link">
    <i class="fas fa-bell"></i> Notifications
    @if(auth()->user()->unreadNotifications->count())
        <span class="nav-badge" id="sidebarNotifBadge">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
    </a>
    <a href="{{ route('mechanic.settings') }}" class="nav-link active">
        <i class="fas fa-cog"></i> Settings
    </a>
    <a href="{{ route('logout') }}" class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
@endsection

@section('content')

<div class="mb-4">
    {{-- <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        <i class="fas fa-cog me-2 text-primary"></i> Settings
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        Manage your account preferences.
    </p> --}}
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Notification Settings --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-bell me-2 text-primary"></i>Notifications</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('mechanic.update-settings') }}">
                    @csrf @method('PATCH')

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                New Service Requests
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified for incoming requests
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_requests" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                New Messages
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified for customer messages
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_messages" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Ratings & Reviews
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified when you receive a review
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_reviews" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Payment Received
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified when payment is received
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_payments" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fixgo mt-3"
                            style="width:auto;padding:10px 24px">
                       <span style="color: white" ><i class="fas fa-save me-2"></i> Save</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Privacy Settings --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-shield-alt me-2 text-primary"></i>Privacy</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('mechanic.update-settings') }}">
                    @csrf @method('PATCH')

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Show Profile to Customers
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Allow customers to view your full profile
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="show_profile"
                                    {{ $settings->show_profile ? 'checked' : '' }}
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Share Live Location
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Allow customers to track you when on the way
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="share_location"
                                   {{ $settings->share_location ? 'checked' : '' }}
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Show Earnings to Admin
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Allow admin to view your earnings reports
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="show_earnings" 
                                    {{ $settings->show_earnings ? 'checked' : '' }}
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fixgo mt-3"
                            style="width:auto;padding:10px 24px">
                        <span style="color: white" ><i class="fas fa-save me-2"></i> Save</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="col-12">
        <div class="fixgo-card" style="border:2px solid #fee2e2">
            <div class="fixgo-card-header" style="background:#fff5f5">
                <h6 style="color:#dc2626">
                    <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
                </h6>
            </div>
            <div class="fixgo-card-body">
                <p style="font-weight:600;font-size:14px;color:#dc2626;margin-bottom:4px">
                    Deactivate Account
                </p>
                <p style="font-size:13px;color:#6b7280;margin-bottom:12px">
                    Deactivating your account will remove you from customer searches.
                    You can reactivate by contacting admin.
                </p>
                <button class="btn btn-outline-danger" style="padding:10px 24px">
                    <i class="fas fa-power-off me-2"></i> Deactivate Account
                </button>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f0f4ff;
    gap: 16px;
}
.setting-item:last-of-type { border-bottom: none; }
</style>
@endpush