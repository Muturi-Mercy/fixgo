@extends('layouts.app')

@section('title', 'Admin Settings')
@section('page-title', 'Settings')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

{{-- <div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        <i class="fas fa-cog me-2 text-primary"></i> Admin Settings
    </h4>
</div> --}}

<div class="row g-4">

    {{-- Admin Profile --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-user me-2 text-primary"></i>Admin Profile</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="#">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Full Name
                        </label>
                        <input type="text" class="form-control"
                               value="{{ auth()->user()->name }}" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Email
                        </label>
                        <input type="email" class="form-control"
                               value="{{ auth()->user()->email }}" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Phone
                        </label>
                        <input type="text" class="form-control"
                               value="{{ auth()->user()->phone }}" name="phone">
                    </div>
                    <button type="submit" class="btn btn-fixgo"
                            style="width:auto;padding:10px 24px">
                       <span style="color: white"> <i class="fas fa-save me-2"></i> Save Profile</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Platform Settings --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-sliders-h me-2 text-primary"></i>Platform Settings</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="setting-item">
                    <div>
                        <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                            Auto-approve Mechanics
                        </p>
                        <p style="font-size:12px;color:#6b7280;margin:0">
                            Automatically approve mechanic registrations
                        </p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               style="width:44px;height:22px;cursor:pointer">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                            Allow New Registrations
                        </p>
                        <p style="font-size:12px;color:#6b7280;margin:0">
                            Allow new users and mechanics to register
                        </p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               checked style="width:44px;height:22px;cursor:pointer">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                            Maintenance Mode
                        </p>
                        <p style="font-size:12px;color:#6b7280;margin:0">
                            Put the platform in maintenance mode
                        </p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               style="width:44px;height:22px;cursor:pointer">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <p style="font-weight:600;font-size:14px;color:#1a3c6e;margin:0">
                            Email Notifications
                        </p>
                        <p style="font-size:12px;color:#6b7280;margin:0">
                            Send email notifications to users
                        </p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                               checked style="width:44px;height:22px;cursor:pointer">
                    </div>
                </div>
                <button class="btn btn-fixgo mt-3" style="width:auto;padding:10px 24px">
                    <span style="color: white"><i class="fas fa-save me-2"></i> Save Settings</span>
                </button>
            </div>
        </div>
    </div>

    {{-- System Info --}}
    <div class="col-12">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>System Information</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;
                                    text-align:center">
                            <p style="font-size:11px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:6px">
                                Laravel Version
                            </p>
                            <p style="font-weight:700;color:#1a3c6e;margin:0">
                                {{ app()->version() }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;
                                    text-align:center">
                            <p style="font-size:11px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:6px">
                                PHP Version
                            </p>
                            <p style="font-weight:700;color:#1a3c6e;margin:0">
                                {{ phpversion() }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;
                                    text-align:center">
                            <p style="font-size:11px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:6px">
                                Environment
                            </p>
                            <p style="font-weight:700;color:#1a3c6e;margin:0">
                                {{ app()->environment() }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;
                                    text-align:center">
                            <p style="font-size:11px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:6px">
                                App Name
                            </p>
                            <p style="font-weight:700;color:#1a3c6e;margin:0">
                                FixGo
                            </p>
                        </div>
                    </div>
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