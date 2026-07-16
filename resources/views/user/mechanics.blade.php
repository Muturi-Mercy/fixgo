@extends('layouts.app')

@section('title', 'Find Mechanics')
@section('page-title', 'Find Mechanics')

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
    <a href="{{ route('user.mechanics') }}" class="nav-link active">
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
        <span class="nav-badge">3</span>
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
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        {{-- <h4 style="color:#1a3c6e; font-weight:700; margin:0">
            <i class="fas fa-search me-2 text-primary"></i> Find Mechanics
        </h4>style="font-size:14px" --}}
        <p class="text-muted mb-0" style="color:#1a3c6e; font-weight:700; margin:0">
            Browse verified mechanics near you.
        </p>
    </div>
</div>

{{-- Search & Filter Bar --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body">
        <form method="GET" action="{{ route('user.mechanics') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="font-size:13px">
                        <i class="fas fa-search me-1 text-primary"></i> Search
                    </label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name or specialty..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="font-size:13px">
                        <i class="fas fa-tools me-1 text-primary"></i> Specialization
                    </label>
                    <select name="specialization" class="form-select form-control">
                        <option value="">All Specializations</option>
                        <option value="Engine" {{ request('specialization')=='Engine' ? 'selected':'' }}>
                            Engine
                        </option>
                        <option value="Electrical" {{ request('specialization')=='Electrical' ? 'selected':'' }}>
                            Electrical
                        </option>
                        <option value="Brakes" {{ request('specialization')=='Brakes' ? 'selected':'' }}>
                            Brakes
                        </option>
                        <option value="Tyres" {{ request('specialization')=='Tyres' ? 'selected':'' }}>
                            Tyres
                        </option>
                        <option value="Bodywork" {{ request('specialization')=='Bodywork' ? 'selected':'' }}>
                            Bodywork
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="font-size:13px">
                        <i class="fas fa-sort me-1 text-primary"></i> Sort By
                    </label>
                    <select name="sort" class="form-select form-control">
                        <option value="rating" {{ request('sort')=='rating' ? 'selected':'' }}>
                            Highest Rated
                        </option>
                        <option value="jobs" {{ request('sort')=='jobs' ? 'selected':'' }}>
                            Most Jobs
                        </option>
                        <option value="experience" {{ request('sort')=='experience' ? 'selected':'' }}>
                            Most Experienced
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-fixgo w-100"
                            style="padding:11px">
                       <span style="color: white"> <i class="fas fa-search me-1"></i> Search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Results Count --}}
<div class="mb-3 d-flex justify-content-between align-items-center">
    <p class="text-muted mb-0" style="font-size:14px">
        <i class="fas fa-info-circle me-1"></i>
        Showing <strong>{{ $mechanics->count() }}</strong> available mechanics
    </p>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="setView('grid')" id="gridBtn">
            <i class="fas fa-th-large"></i>
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="setView('list')" id="listBtn">
            <i class="fas fa-list"></i>
        </button>
    </div>
</div>

{{-- Mechanics Grid --}}
@if($mechanics->count())
<div class="row g-4" id="mechanicsContainer">
    @foreach($mechanics as $mechanic)
    <div class="col-md-6 col-lg-4 mechanic-item">
        <div class="mechanic-card">

            {{-- Cover / Header --}}
            <div class="mechanic-card-header">
                <div class="mechanic-avatar">
                    @if($mechanic->user->profile_photo)
                        <img src="{{ asset('storage/'.$mechanic->user->profile_photo) }}"
                             alt="{{ $mechanic->user->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="mechanic-availability-badge
                     {{ $mechanic->availability === 'available' ? 'available' : 'busy' }}">
                    <i class="fas fa-circle me-1" style="font-size:8px"></i>
                    {{ ucfirst($mechanic->availability) }}
                </div>
                {{-- Favourite Button --}}
                <button class="mechanic-fav-btn" onclick="toggleFavourite({{ $mechanic->id }}, this)">
                    <i class="fas fa-heart"></i>
                </button>
            </div>

            {{-- Info --}}
            <div class="mechanic-card-body">
                <h6 class="mechanic-name" style="padding-top: 30px; padding-bottom:10px">{{ $mechanic->user->name }}</h6>

                {{-- Rating --}}
                <div class="mechanic-rating mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $mechanic->rating ? 'text-warning' : 'text-muted' }}"
                           style="font-size:13px"></i>
                    @endfor
                    <span style="font-size:13px;font-weight:600;color:#1a3c6e;margin-left:4px">
                        {{ number_format($mechanic->rating, 1) }}
                    </span>
                    <span style="font-size:12px;color:#6b7280">
                        ({{ $mechanic->ratings->count() }} reviews)
                    </span>
                </div>

                {{-- Location --}}
                <p class="mechanic-location">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    {{ $mechanic->location_address ?? 'Location not set' }}
                </p>

                {{-- Specialization Tags --}}
                @if($mechanic->specialization)
                <div class="mechanic-tags mb-3">
                    @foreach(explode(',', $mechanic->specialization) as $spec)
                        <span class="mechanic-tag">{{ trim($spec) }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Stats --}}
                <div class="mechanic-stats">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->years_of_experience ?? 0 }}
                        </span>
                        <span class="mechanic-stat-label">Yrs Exp</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $mechanic->total_jobs }}</span>
                        <span class="mechanic-stat-label">Jobs Done</span>
                    </div>
                    {{-- <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->response_time ?? '—' }}
                        </span>
                        <span class="mechanic-stat-label">Min Response</span>
                    </div> --}}
                </div>

                {{-- Price Range --}}
                @if($mechanic->min_price && $mechanic->max_price)
                <div class="mechanic-price">
                    <i class="fas fa-tag me-1 text-primary"></i>
                    KSh {{ number_format($mechanic->min_price) }} –
                    KSh {{ number_format($mechanic->max_price) }}
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('user.mechanic-profile', $mechanic->id) }}"
                       class="btn btn-outline-primary btn-sm flex-1">
                        <i class="fas fa-user me-1"></i> View Profile
                    </a>
                    <a href="{{ route('user.request-assistance') }}"
                       class="btn btn-fixgo btn-sm flex-1"
                       style="width:auto;padding:7px 14px">
                       <span style="color: white"> <i class="fas fa-tools me-1"></i> Request</span>
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
        <i class="fas fa-search fa-4x text-muted mb-4"></i>
        <h5 style="color:#1a3c6e;font-weight:700">No Mechanics Found</h5>
        <p class="text-muted">
            No available mechanics match your search. Try adjusting your filters.
        </p>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
/* Mechanic Card */
.mechanic-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.06);
    border: 1px solid #f0f4ff;
    transition: all 0.3s ease;
}
.mechanic-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    border-color: #3b82f6;
}
.mechanic-card-header {
    background: linear-gradient(135deg, #1a3c6e, #3b82f6);
    padding: 30px 20px 50px;
    position: relative;
    text-align: center;
}
.mechanic-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: white;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #1a3c6e;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    overflow: hidden;
}
.mechanic-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.mechanic-availability-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.mechanic-availability-badge.available {
    background: #d1fae5;
    color: #065f46;
}
.mechanic-availability-badge.busy {
    background: #fee2e2;
    color: #991b1b;
}
.mechanic-fav-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mechanic-fav-btn:hover,
.mechanic-fav-btn.active {
    background: #ef4444;
    color: white;
}
.mechanic-card-body {
    padding: 16px 20px 20px;
    margin-top: -30px;
    position: relative;
}
.mechanic-name {
    font-size: 16px;
    font-weight: 700;
    color: #1a3c6e;
    margin-bottom: 6px;
    text-align: center;
}
.mechanic-rating {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
}
.mechanic-location {
    font-size: 12px;
    color: #6b7280;
    text-align: center;
    margin-bottom: 10px;
}
.mechanic-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}
.mechanic-tag {
    background: #eff6ff;
    color: #1a3c6e;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.mechanic-stats {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 12px 0;
    border-top: 1px solid #f0f4ff;
    border-bottom: 1px solid #f0f4ff;
    margin-bottom: 12px;
}
.mechanic-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}
.mechanic-stat-value {
    font-size: 16px;
    font-weight: 800;
    color: #1a3c6e;
}
.mechanic-stat-label {
    font-size: 10px;
    color: #6b7280;
    text-align: center;
}
.mechanic-stat-divider {
    width: 1px;
    height: 30px;
    background: #e5e7eb;
}
.mechanic-price {
    font-size: 13px;
    color: #374151;
    font-weight: 600;
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script>
function toggleFavourite(mechanicId, btn) {
    fetch(`/user/favourite/${mechanicId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.toggle('active', data.favourited);
    });
}

function setView(type) {
    const container = document.getElementById('mechanicsContainer');
    const items = document.querySelectorAll('.mechanic-item');
    if (type === 'list') {
        items.forEach(item => {
            item.className = 'col-12 mechanic-item';
        });
        document.getElementById('listBtn').classList.replace('btn-outline-secondary', 'btn-outline-primary');
        document.getElementById('gridBtn').classList.replace('btn-outline-primary', 'btn-outline-secondary');
    } else {
        items.forEach(item => {
            item.className = 'col-md-6 col-lg-4 mechanic-item';
        });
        document.getElementById('gridBtn').classList.replace('btn-outline-secondary', 'btn-outline-primary');
        document.getElementById('listBtn').classList.replace('btn-outline-primary', 'btn-outline-secondary');
    }
}
</script>
@endpush