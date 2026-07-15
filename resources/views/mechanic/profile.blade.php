@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

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
    <a href="{{ route('mechanic.profile') }}" class="nav-link active">
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

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="fixgo-card text-center">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:40px 20px 60px;border-radius:14px 14px 0 0"></div>
            <div style="margin-top:-50px;padding:0 20px 24px">

                {{-- Avatar --}}
                <div class="position-relative d-inline-block mb-3">
                    <div style="width:100px;height:100px;border-radius:50%;
                                border:4px solid white;
                                box-shadow:0 4px 15px rgba(0,0,0,0.15);
                                overflow:hidden;margin:0 auto;
                                background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                                display:flex;align-items:center;justify-content:center;">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}"
                                 alt="Profile"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="fas fa-user" style="font-size:40px;color:white"></i>
                        @endif
                    </div>
                    <label for="photoUpload"
                           style="position:absolute;bottom:0;right:0;
                                  width:30px;height:30px;background:#f97316;
                                  border-radius:50%;display:flex;
                                  align-items:center;justify-content:center;
                                  cursor:pointer;border:2px solid white">
                        <i class="fas fa-camera text-white" style="font-size:12px"></i>
                    </label>
                    <form id="photoForm" method="POST"
                          action="{{ route('mechanic.update-photo') }}"
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

                @if($mechanic->verification_status === 'approved')
                <span style="background:#10b981;color:white;padding:4px 12px;
                             border-radius:20px;font-size:12px;font-weight:600">
                    <i class="fas fa-check-circle me-1"></i> Verified Mechanic
                </span>
                @elseif($mechanic->verification_status === 'pending')
                <span style="background:#f59e0b;color:white;padding:4px 12px;
                             border-radius:20px;font-size:12px;font-weight:600">
                    <i class="fas fa-clock me-1"></i> Verification Pending
                </span>
                @else
                <span style="background:#ef4444;color:white;padding:4px 12px;
                             border-radius:20px;font-size:12px;font-weight:600">
                    <i class="fas fa-times-circle me-1"></i> Not Verified
                </span>
                @endif

                {{-- Stats --}}
                <div class="mechanic-stats mt-4">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ number_format($mechanic->rating ?? 0, 1) }}
                        </span>
                        <span class="mechanic-stat-label">Rating</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->total_jobs }}
                        </span>
                        <span class="mechanic-stat-label">Jobs</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->years_of_experience ?? 0 }}
                        </span>
                        <span class="mechanic-stat-label">Yrs Exp</span>
                    </div>
                </div>

                {{-- Availability --}}
                <div class="mt-3">
                    <span class="status-badge
                          {{ $mechanic->availability === 'available' ? 'badge-available' :
                             ($mechanic->availability === 'busy' ? 'badge-busy' : 'badge-offline') }}">
                        <i class="fas fa-circle me-1" style="font-size:8px"></i>
                        {{ ucfirst($mechanic->availability) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Form --}}
    <div class="col-lg-8">
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-edit me-2 text-primary"></i>Edit Profile</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('mechanic.update-profile') }}">
                    @csrf @method('PATCH')
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-user me-1 text-primary"></i> Full Name
                            </label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-phone me-1 text-primary"></i> Phone
                            </label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', auth()->user()->phone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-tools me-1 text-primary"></i>
                                Specialization
                            </label>
                            <input type="text" name="specialization" class="form-control"
                                   value="{{ old('specialization', $mechanic->specialization) }}"
                                   placeholder="e.g. Engine, Electrical, Brakes">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                Years of Experience
                            </label>
                            <input type="number" name="years_of_experience"
                                   class="form-control" min="0" max="50"
                                   value="{{ old('years_of_experience',
                                                  $mechanic->years_of_experience) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                Location / Service Area
                            </label>
                            <input type="text" name="location_address" class="form-control"
                                   value="{{ old('location_address',
                                                  $mechanic->location_address) }}"
                                   placeholder="e.g. Nairobi, Kenya">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-tag me-1 text-primary"></i>
                                Min Price (KSh)
                            </label>
                            <input type="number" name="min_price" class="form-control"
                                   value="{{ old('min_price', $mechanic->min_price) }}"
                                   placeholder="500">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-tag me-1 text-primary"></i>
                                Max Price (KSh)
                            </label>
                            <input type="number" name="max_price" class="form-control"
                                   value="{{ old('max_price', $mechanic->max_price) }}"
                                   placeholder="5000">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-comment me-1 text-primary"></i> Bio
                            </label>
                            <textarea name="bio" class="form-control" rows="3"
                                      placeholder="Tell customers about yourself...">{{ old('bio', $mechanic->bio) }}</textarea>
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
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-lock me-2 text-primary"></i>Change Password</h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('mechanic.update-password') }}">
                    @csrf @method('PATCH')
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
                                   placeholder="Confirm password">
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