@extends('layouts.app')

@section('title', 'Favourites')
@section('page-title', 'Favourites')

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
    <a href="{{ route('user.favourites') }}" class="nav-link active">
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
    <a href="#" class="nav-link">
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
        {{-- <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            <i class="fas fa-heart me-2 text-danger"></i> Favourite Mechanics
        </h4>
        <p class="text-muted mb-0" style="font-size:14px">
            Your saved mechanics for quick access.
        </p> --}}
    </div>
    <a href="{{ route('user.mechanics') }}" class="btn btn-outline-primary"
       style="padding:10px 20px">
        <i class="fas fa-search me-2"></i> Find More
    </a>
</div>

@if($favourites->count())
    <div class="row g-4">
        @foreach($favourites as $fav)
        <div class="col-md-6 col-lg-4">
            <div class="mechanic-card">
                <div class="mechanic-card-header">
                    <div class="mechanic-avatar mx-auto">
                        @if($fav->mechanic->user->profile_photo)
                            <img src="{{ asset('storage/'.$fav->mechanic->user->profile_photo) }}"
                                 alt="{{ $fav->mechanic->user->name }}">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="mechanic-availability-badge
                         {{ $fav->mechanic->availability === 'available' ? 'available' : 'busy' }}">
                        <i class="fas fa-circle me-1" style="font-size:8px"></i>
                        {{ ucfirst($fav->mechanic->availability) }}
                    </div>
                    {{-- Remove from favourites --}}
                    <form method="POST"
                          action="{{ route('user.toggle-favourite', $fav->mechanic->id) }}"
                          style="position:absolute;top:10px;right:10px">
                        @csrf
                        <button type="submit" class="mechanic-fav-btn active"
                                title="Remove from favourites">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="mechanic-card-body">
                    <h6 class="mechanic-name">{{ $fav->mechanic->user->name }}</h6>
                    <div class="mechanic-rating mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star
                               {{ $i <= $fav->mechanic->rating ? 'text-warning' : 'text-muted' }}"
                               style="font-size:13px"></i>
                        @endfor
                        <span style="font-size:13px;font-weight:600;color:#1a3c6e;margin-left:4px">
                            {{ number_format($fav->mechanic->rating, 1) }}
                        </span>
                    </div>
                    <p class="mechanic-location">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        {{ $fav->mechanic->location_address ?? 'Location not set' }}
                    </p>
                    @if($fav->mechanic->specialization)
                    <div class="mechanic-tags mb-3">
                        @foreach(explode(',', $fav->mechanic->specialization) as $spec)
                            <span class="mechanic-tag">{{ trim($spec) }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="mechanic-stats">
                        <div class="mechanic-stat">
                            <span class="mechanic-stat-value">
                                {{ $fav->mechanic->years_of_experience ?? 0 }}
                            </span>
                            <span class="mechanic-stat-label">Yrs Exp</span>
                        </div>
                        <div class="mechanic-stat-divider"></div>
                        <div class="mechanic-stat">
                            <span class="mechanic-stat-value">
                                {{ $fav->mechanic->total_jobs }}
                            </span>
                            <span class="mechanic-stat-label">Jobs Done</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('user.mechanic-profile', $fav->mechanic->id) }}"
                           class="btn btn-outline-primary btn-sm flex-1">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                        <a href="{{ route('user.request-assistance') }}"
                           class="btn btn-fixgo btn-sm flex-1"
                           style="width:auto;padding:7px 14px">
                            <i class="fas fa-tools me-1"></i> Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="fixgo-card">
        <div class="fixgo-card-body text-center py-5">
            <i class="fas fa-heart fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Favourites Yet</h5>
            <p class="text-muted mb-4">
                Save mechanics you trust for quick access next time.
            </p>
            <a href="{{ route('user.mechanics') }}"
               class="btn btn-fixgo" style="width:auto;padding:12px 30px">
                <span style="color: white"><i class="fas fa-search me-2"></i> Find Mechanics</span>
            </a>
        </div>
    </div>
@endif

@endsection