@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

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
    <a href="{{ route('user.notifications') }}" class="nav-link">
        <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('user.profile') }}" class="nav-link">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('user.settings') }}" class="nav-link active">
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
    </h4> style="font-size:14px">--}}
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0x">
        Manage your account preferences and settings.
    </p>
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
                <h6><i class="fas fa-bell me-2 text-primary"></i>Notification Settings</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('user.update-settings') }}">
                    @csrf @method('PATCH')

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Request Updates
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified when your request status changes
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_request_updates" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Mechanic Nearby
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified when mechanic is nearby
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_mechanic_nearby" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Chat Messages
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Get notified for new messages
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_chat" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Promotions & Offers
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Receive promotional notifications
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="notify_promotions"
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fixgo mt-3"
                            style="width:auto;padding:10px 24px">
                        <span style="color: white"><i class="fas fa-save me-2"></i> Save Notifications</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Privacy Settings --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-shield-alt me-2 text-primary"></i>Privacy Settings</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('user.update-settings') }}">
                    @csrf @method('PATCH')

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Share Location
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Allow mechanics to see your location
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="share_location" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Show Profile to Mechanics
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Allow mechanics to view your profile
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="show_profile" checked
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <div class="setting-item">
                        <div>
                            <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                                Two Factor Authentication
                            </p>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Add extra security to your account
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   name="two_factor"
                                   style="width:44px;height:22px;cursor:pointer">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fixgo mt-3"
                            style="width:auto;padding:10px 24px">
                       <span style="color: white" ><i class="fas fa-save me-2"></i> Save Privacy</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Appearance Settings --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-palette me-2 text-primary"></i>Appearance</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">
                        Language
                    </label>
                    <select class="form-select form-control">
                        <option selected>English</option>
                        <option>Swahili</option>
                        <option>French</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">
                        Currency
                    </label>
                    <select class="form-select form-control">
                        <option selected>KSh - Kenyan Shilling</option>
                        <option>USD - US Dollar</option>
                        <option>EUR - Euro</option>
                    </select>
                </div>
                <button class="btn btn-fixgo" style="width:auto;padding:10px 24px">
                    <span style="color: white"><i class="fas fa-save me-2"></i> Save Appearance</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="col-lg-6">
        <div class="fixgo-card" style="border:2px solid #fee2e2">
            <div class="fixgo-card-header" style="background:#fff5f5">
                <h6 style="color:#dc2626">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </h6>
            </div>
            <div class="fixgo-card-body">
                <div class="mb-3">
                    <p style="font-weight:600;font-size:14px;color:#dc2626;margin-bottom:4px">
                        Delete Account
                    </p>
                    <p style="font-size:13px;color:#6b7280;margin-bottom:12px">
                        Once deleted, your account and all data will be permanently removed.
                        This action cannot be undone.
                    </p>
                    <button class="btn btn-outline-danger"
                            onclick="confirmDelete()"
                            style="padding:10px 24px">
                        <i class="fas fa-trash me-2"></i> Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Delete Account Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header"
                 style="background:#dc2626;color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i> Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p style="color:#374151">
                    Are you absolutely sure you want to delete your account?
                    All your data including requests, reviews and favourites
                    will be permanently deleted.
                </p>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px">
                        Type <strong>DELETE</strong> to confirm
                    </label>
                    <input type="text" id="deleteConfirmInput"
                           class="form-control" placeholder="Type DELETE here">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-danger flex-1"
                            onclick="deleteAccount()">
                        <i class="fas fa-trash me-2"></i> Yes, Delete Account
                    </button>
                    <button class="btn btn-outline-secondary flex-1"
                            data-bs-dismiss="modal">Cancel</button>
                </div>
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

@push('scripts')
<script>
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function deleteAccount() {
    const input = document.getElementById('deleteConfirmInput').value;
    if (input !== 'DELETE') {
        alert('Please type DELETE to confirm.');
        return;
    }
    // Will implement delete account route later
    alert('Account deletion will be implemented in the backend phase.');
}
</script>
@endpush