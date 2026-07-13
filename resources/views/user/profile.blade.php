@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

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
    <a href="{{ route('user.profile') }}" class="nav-link active">
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

{{-- Success/Error Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Left: Profile Card --}}
    <div class="col-lg-4">
        <div class="fixgo-card text-center">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:40px 20px 60px;border-radius:14px 14px 0 0">
            </div>
            <div style="margin-top:-50px;padding:0 20px 20px">
                {{-- Avatar --}}
                <div class="position-relative d-inline-block mb-3">
                <div style="width:100px;height:100px;border-radius:50%;
                            border:4px solid white;box-shadow:0 4px 15px rgba(0,0,0,0.15);
                            overflow:hidden;margin:0 auto;background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                            display:flex;align-items:center;justify-content:center;">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}"
                            alt="Profile"
                            style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                        <i class="fas fa-user" style="font-size:40px;color:white;"></i>
                    @endif
                </div>
                    <label for="photoUpload"
                           style="position:absolute;bottom:0;right:0;
                                  width:30px;height:30px;background:#f97316;
                                  border-radius:50%;display:flex;align-items:center;
                                  justify-content:center;cursor:pointer;
                                  border:2px solid white">
                        <i class="fas fa-camera text-white" style="font-size:12px"></i>
                    </label>
                    <form id="photoForm" method="POST"
                          action="{{ route('user.update-photo') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="photoUpload" name="profile_photo"
                               accept="image/*" class="d-none"
                               onchange="document.getElementById('photoForm').submit()">
                    </form>
                </div>

                <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:4px">
                    {{ auth()->user()->name }}
                </h5>
                <p class="text-muted mb-1" style="font-size:14px">
                    {{ auth()->user()->email }}
                </p>
                <p class="text-muted mb-3" style="font-size:13px">
                    <i class="fas fa-phone me-1"></i>
                    {{ auth()->user()->phone ?? 'No phone added' }}
                </p>

                <span class="status-badge badge-available">
                    <i class="fas fa-check-circle me-1"></i> Active Account
                </span>

                {{-- Stats --}}
                <div class="mechanic-stats mt-4">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $totalRequests }}</span>
                        <span class="mechanic-stat-label">Requests</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $completedRequests }}</span>
                        <span class="mechanic-stat-label">Completed</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $favourites }}</span>
                        <span class="mechanic-stat-label">Favourites</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Edit Profile Form --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-edit me-2 text-primary"></i>Edit Profile</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('user.update-profile') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-user me-1 text-primary"></i> Full Name
                            </label>
                            <input type="text" name="name" class="form-control
                                   @error('name') is-invalid @enderror"
                                   value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control
                                   @error('email') is-invalid @enderror"
                                   value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-phone me-1 text-primary"></i> Phone Number
                            </label>
                            <input type="text" name="phone" class="form-control
                                   @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   placeholder="e.g. 0712345678">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Member Since --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-calendar me-1 text-primary"></i> Member Since
                            </label>
                            <input type="text" class="form-control"
                                   value="{{ auth()->user()->created_at->format('M d, Y') }}"
                                   disabled style="background:#f9fafb">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-fixgo"
                                    style="width:auto;padding:11px 30px">
                                <span style="color: white"><i class="fas fa-save me-2"></i> Save Changes</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="fixgo-card mt-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-lock me-2 text-primary"></i>Change Password</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('user.update-password') }}">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size:13px">
                                Current Password
                            </label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Current password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size:13px">
                                New Password
                            </label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="New password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size:13px">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm new password">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary"
                                    style="padding:11px 30px">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection