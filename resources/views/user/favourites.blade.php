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
        @if(auth()->user()->unreadNotifications->count())
            <span class="nav-badge" id="sidebarNotifBadge">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
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

{{-- Header --}}
{{-- <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            <i class="fas fa-heart me-2 text-danger"></i> Favourite Mechanics
        </h4>
        <p class="text-muted mb-0" style="font-size:14px">
            Your saved mechanics for quick access.
        </p>
    </div>
    <a href="{{ route('user.mechanics') }}" class="btn btn-outline-primary"
       style="padding:10px 20px">
        <i class="fas fa-search me-2"></i> Find More
    </a>
</div> --}}

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
{{-- <div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-heart"></i></div>
            <div class="stat-info">
                <h3>{{ $favourites->count() }}</h3>
                <p>Saved Mechanics</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $favourites->filter(fn($f) => $f->mechanic->availability === 'available')->count() }}</h3>
                <p>Available Now</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>{{ $favourites->count() ? number_format($favourites->avg(fn($f) => $f->mechanic->rating), 1) : '0.0' }}</h3>
                <p>Avg Rating</p>
            </div>
        </div>
    </div>
</div> --}}

@if($favourites->count())
    <div class="row g-4">
        @foreach($favourites as $fav)
        <div class="col-md-6 col-lg-4">
            <div class="fixgo-card h-100" style="overflow:hidden;transition:all 0.3s ease"
                 onmouseover="this.style.transform='translateY(-4px)';
                              this.style.boxShadow='0 12px 30px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='';this.style.boxShadow=''">

                {{-- Card Header with gradient --}}
                <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                            padding:24px 20px 40px;text-align:center;position:relative">

                    {{-- Availability Badge --}}
                    <div style="position:absolute;top:12px;left:12px">
                        <span style="background:{{ $fav->mechanic->availability === 'available' ? '#10b981' : '#ef4444' }};
                                     color:white;padding:4px 10px;border-radius:20px;
                                     font-size:11px;font-weight:700">
                            <i class="fas fa-circle me-1" style="font-size:7px"></i>
                            {{ ucfirst($fav->mechanic->availability) }}
                        </span>
                    </div>

                    {{-- Remove Button --}}
                    <div style="position:absolute;top:10px;right:10px">
                        <form method="POST"
                              action="{{ route('user.toggle-favourite', $fav->mechanic->id) }}"
                              onsubmit="return confirm('Remove from favourites?')">
                            @csrf
                            <button type="submit"
                                    title="Remove from favourites"
                                    style="background:rgba(255,255,255,0.2);border:none;
                                           width:32px;height:32px;border-radius:50%;
                                           color:white;cursor:pointer;
                                           display:flex;align-items:center;
                                           justify-content:center;transition:all 0.2s"
                                    onmouseover="this.style.background='#ef4444'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                                <i class="fas fa-heart-broken" style="font-size:13px"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Avatar --}}
                    <div style="width:80px;height:80px;border-radius:50%;
                                border:4px solid rgba(255,255,255,0.8);
                                margin:0 auto;overflow:hidden;
                                background:rgba(255,255,255,0.2);
                                display:flex;align-items:center;justify-content:center;
                                box-shadow:0 4px 15px rgba(0,0,0,0.2)">
                        @if($fav->mechanic->user->profile_photo)
                            <img src="{{ asset('storage/'.$fav->mechanic->user->profile_photo) }}"
                                 alt="{{ $fav->mechanic->user->name }}"
                                 style="width:100%;height:100%;object-fit:cover">
                        @else
                            <i class="fas fa-user" style="font-size:32px;color:white"></i>
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div style="padding:16px 20px 20px;margin-top:-20px;
                            background:white;border-radius:20px 20px 0 0;
                            position:relative">

                    {{-- Name & Rating --}}
                    <div class="text-center mb-3">
                        <h6 style="font-weight:700;color:#1a3c6e;
                                   margin:0 0 6px;font-size:16px">
                            {{ $fav->mechanic->user->name }}
                        </h6>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star {{ $i <= $fav->mechanic->rating ? 'text-warning' : 'text-muted' }}"
                                   style="font-size:13px"></i>
                            @endfor
                            <span style="font-size:13px;font-weight:700;
                                         color:#1a3c6e;margin-left:4px">
                                {{ number_format($fav->mechanic->rating,1) }}
                            </span>
                        </div>
                    </div>

                    {{-- Location --}}
                    <p style="font-size:12px;color:#6b7280;text-align:center;margin-bottom:12px">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        {{ $fav->mechanic->location_address ?? 'Location not set' }}
                    </p>

                    {{-- Specializations --}}
                    @if($fav->mechanic->specialization)
                    <div class="d-flex flex-wrap gap-1 justify-content-center mb-3">
                        @foreach(array_slice(explode(',', $fav->mechanic->specialization), 0, 3) as $spec)
                            <span style="background:#eff6ff;color:#1a3c6e;
                                         padding:3px 10px;border-radius:20px;
                                         font-size:11px;font-weight:600">
                                {{ trim($spec) }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Stats Row --}}
                    <div style="display:flex;justify-content:space-around;
                                padding:12px 0;margin-bottom:14px;
                                border-top:1px solid #f0f4ff;
                                border-bottom:1px solid #f0f4ff">
                        <div style="text-align:center">
                            <div style="font-size:18px;font-weight:800;color:#1a3c6e">
                                {{ $fav->mechanic->years_of_experience ?? 0 }}
                            </div>
                            <div style="font-size:10px;color:#6b7280;font-weight:600">
                                Yrs Exp
                            </div>
                        </div>
                        <div style="width:1px;background:#e5e7eb"></div>
                        <div style="text-align:center">
                            <div style="font-size:18px;font-weight:800;color:#1a3c6e">
                                {{ $fav->mechanic->total_jobs }}
                            </div>
                            <div style="font-size:10px;color:#6b7280;font-weight:600">
                                Jobs Done
                            </div>
                        </div>
                        <div style="width:1px;background:#e5e7eb"></div>
                        <div style="text-align:center">
                            <div style="font-size:18px;font-weight:800;color:#1a3c6e">
                                {{ $fav->mechanic->response_time ?? '—' }}
                            </div>
                            <div style="font-size:10px;color:#6b7280;font-weight:600">
                                Min Resp
                            </div>
                        </div>
                    </div>

                    {{-- Price Range --}}
                    @if($fav->mechanic->min_price && $fav->mechanic->max_price)
                    <div class="text-center mb-3">
                        <span style="background:#f0fdf4;color:#065f46;
                                     padding:5px 14px;border-radius:20px;
                                     font-size:12px;font-weight:700;
                                     border:1px solid #10b981">
                            <i class="fas fa-tag me-1"></i>
                            KSh {{ number_format($fav->mechanic->min_price) }} –
                            KSh {{ number_format($fav->mechanic->max_price) }}
                        </span>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.mechanic-profile', $fav->mechanic->id) }}"
                           class="btn btn-outline-primary btn-sm"
                           style="flex:1;padding:9px">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                        <a href="{{ route('user.request-assistance', $fav->mechanic->id) }}"
                           class="btn btn-fixgo btn-sm"
                           style="flex:1;padding:9px;width:auto">
                            <i class="fas fa-tools me-1"></i> Request
                        </a>
                    </div>

                    {{-- Remove Button Below --}}
                    <form method="POST"
                          action="{{ route('user.toggle-favourite', $fav->mechanic->id) }}"
                          class="mt-2"
                          onsubmit="return confirm('Remove {{ $fav->mechanic->user->name }} from favourites?')">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm w-100"
                                style="background:#fff0f0;color:#ef4444;
                                       border:1px solid #fecaca;border-radius:8px;
                                       font-size:12px;font-weight:600;padding:7px">
                            <i class="fas fa-heart-broken me-1"></i>
                            Remove from Favourites
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@else
    {{-- Empty State --}}
    <div class="fixgo-card">
        <div class="fixgo-card-body text-center py-5">
            <div style="width:80px;height:80px;background:#fef2f2;border-radius:50%;
                        margin:0 auto 20px;display:flex;align-items:center;
                        justify-content:center">
                <i class="fas fa-heart fa-2x" style="color:#ef4444"></i>
            </div>
            <h5 style="color:#1a3c6e;font-weight:700;margin-bottom:8px">
                No Favourites Yet
            </h5>
            <p class="text-muted mb-4" style="max-width:350px;margin:0 auto 20px">
                Save mechanics you trust for quick access next time.
                Browse available mechanics and tap the heart icon to save them here.
            </p>
            <a href="{{ route('user.mechanics') }}"
               class="btn btn-fixgo" style="width:auto;padding:12px 30px">
                <i class="fas fa-search me-2"></i> Find Mechanics
            </a>
        </div>
    </div>
@endif

@endsection