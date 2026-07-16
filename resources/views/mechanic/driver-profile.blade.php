@extends('layouts.app')

@section('title', 'Driver Profile')
@section('page-title', 'Driver Profile')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link active">
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
    <a href="#" class="nav-link">
        <i class="fas fa-cog"></i> Settings
    </a>
    <a href="{{ route('logout') }}" class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
@endsection

@section('content')

<div class="mb-4">
    <a href="javascript:history.back()"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Go Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="fixgo-card">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:40px 20px 60px;border-radius:14px 14px 0 0;
                        text-align:center">
            </div>

            <div style="margin-top:-50px;padding:0 30px 30px;text-align:center">

                {{-- Avatar --}}
                <div style="width:90px;height:90px;border-radius:50%;
                            border:4px solid white;
                            box-shadow:0 4px 15px rgba(0,0,0,0.15);
                            overflow:hidden;margin:0 auto 16px;
                            background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                            display:flex;align-items:center;justify-content:center;">
                    @if($driver->profile_photo)
                        <img src="{{ asset('storage/'.$driver->profile_photo) }}"
                             style="width:100%;height:100%;object-fit:cover">
                    @else
                        <i class="fas fa-user" style="font-size:36px;color:white"></i>
                    @endif
                </div>

                <h4 style="font-weight:700;color:#1a3c6e;margin-bottom:4px">
                    {{ $driver->name }}
                </h4>
                <p class="text-muted mb-1" style="font-size:14px">
                    <i class="fas fa-car me-1"></i> Driver
                </p>
                <p class="text-muted mb-3" style="font-size:13px">
                    Member since {{ $driver->created_at->format('M Y') }}
                </p>

                <span class="status-badge badge-available mb-4 d-inline-block">
                    <i class="fas fa-check-circle me-1"></i> Active Account
                </span>

                {{-- Stats --}}
                {{-- <div class="mechanic-stats my-4">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $totalRequests }}</span>
                        <span class="mechanic-stat-label">Total Requests</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $completedRequests }}</span>
                        <span class="mechanic-stat-label">Completed</span>
                    </div>
                </div> --}}

                {{-- Contact Info --}}
                <div class="text-start p-3 mb-4"
                     style="background:#f8fafc;border-radius:12px">
                    <div class="confirm-row">
                        <span class="confirm-label">
                            <i class="fas fa-envelope me-2 text-primary"></i> Email
                        </span>
                        <span class="confirm-value">{{ $driver->email }}</span>
                    </div>
                    <div class="confirm-row">
                        <span class="confirm-label">
                            <i class="fas fa-phone me-2 text-primary"></i> Phone
                        </span>
                        <span class="confirm-value">
                            {{ $driver->phone ?? 'Not provided' }}
                        </span>
                    </div>
                </div>

                {{-- Action --}}
                @if($driver->phone)
                <a href="tel:{{ $driver->phone }}"
                   class="btn btn-outline-success w-100">
                    <i class="fas fa-phone me-2"></i> Call Driver
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection