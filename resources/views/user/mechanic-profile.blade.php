@extends('layouts.app')

@section('title', 'Mechanic Profile')
@section('page-title', 'Mechanic Profile')

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

<div class="row g-4">

    {{-- Left Column: Profile Card --}}
    <div class="col-lg-4">

        {{-- Profile Card --}}
        <div class="fixgo-card mb-4">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:30px 20px;text-align:center;border-radius:14px 14px 0 0">
                <div class="mechanic-avatar mx-auto mb-3"
                     style="width:90px;height:90px;font-size:36px">
                    @if($mechanic->user->profile_photo)
                        <img src="{{ asset('storage/'.$mechanic->user->profile_photo) }}"
                             alt="{{ $mechanic->user->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <h5 style="color:white;font-weight:700;margin-bottom:4px">
                    {{ $mechanic->user->name }}
                </h5>
                @if($mechanic->verification_status === 'approved')
                <span style="background:#10b981;color:white;padding:4px 12px;
                             border-radius:20px;font-size:12px;font-weight:600">
                    <i class="fas fa-check-circle me-1"></i> Verified Mechanic
                </span>
                @endif
            </div>
            <div class="fixgo-card-body">

                {{-- Rating --}}
                <div class="text-center mb-3">
                    <div class="d-flex justify-content-center gap-1 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $mechanic->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <span style="font-size:14px;color:#6b7280">
                        {{ number_format($mechanic->rating, 1) }}
                        ({{ $mechanic->ratings->count() }} Reviews)
                    </span>
                </div>

                {{-- Location --}}
                <div class="d-flex align-items-center gap-2 mb-3 p-2"
                     style="background:#f8fafc;border-radius:8px">
                    <i class="fas fa-map-marker-alt text-danger"></i>
                    <span style="font-size:13px;color:#374151">
                        {{ $mechanic->location_address ?? 'Location not set' }}
                    </span>
                </div>

                {{-- Specializations --}}
                @if($mechanic->specialization)
                <div class="mb-3">
                    <p class="text-muted mb-2" style="font-size:12px;font-weight:600">
                        SPECIALIZATIONS
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(explode(',', $mechanic->specialization) as $spec)
                            <span class="mechanic-tag">{{ trim($spec) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Stats --}}
                <div class="mechanic-stats mb-3">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->years_of_experience ?? 0 }}
                        </span>
                        <span class="mechanic-stat-label">Years Exp</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $mechanic->total_jobs }}</span>
                        <span class="mechanic-stat-label">Jobs Done</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->response_time ?? '—' }}
                        </span>
                        <span class="mechanic-stat-label">Min Response</span>
                    </div>
                </div>

                {{-- Price --}}
                @if($mechanic->min_price && $mechanic->max_price)
                <div class="text-center mb-3 p-2"
                     style="background:#eff6ff;border-radius:8px">
                    <span style="font-size:13px;font-weight:600;color:#1a3c6e">
                        <i class="fas fa-tag me-1"></i>
                        KSh {{ number_format($mechanic->min_price) }} –
                        KSh {{ number_format($mechanic->max_price) }}
                    </span>
                </div>
                @endif

                {{-- Action Buttons --}}
                <a href="{{ route('user.request-assistance') }}"
                   class="btn btn-fixgo w-100 mb-2">
                    <i class="fas fa-tools me-2"></i> Request Service
                </a>
                <button class="btn btn-outline-primary w-100"
                        onclick="toggleFavourite({{ $mechanic->id }}, this)">
                    <i class="fas fa-heart me-2"></i> Save to Favourites
                </button>
            </div>
        </div>

        {{-- Bio Card --}}
        @if($mechanic->bio)
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>About</h6>
            </div>
            <div class="fixgo-card-body">
                <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">
                    {{ $mechanic->bio }}
                </p>
            </div>
        </div>
        @endif

    </div>

    {{-- Right Column: Portfolio & Reviews --}}
    <div class="col-lg-8">

        {{-- Tabs --}}
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-body" style="padding:8px 16px">
                <ul class="nav nav-pills gap-2" id="profileTabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#portfolio" data-bs-toggle="tab">
                            <i class="fas fa-images me-1"></i> Portfolio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reviews" data-bs-toggle="tab">
                            <i class="fas fa-star me-1"></i>
                            Reviews ({{ $mechanic->ratings->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about" data-bs-toggle="tab">
                            <i class="fas fa-user me-1"></i> About
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">

            {{-- Portfolio Tab --}}
            <div class="tab-pane fade show active" id="portfolio">
                @if($mechanic->portfolios->count())
                    @foreach($mechanic->portfolios as $portfolio)
                    <div class="fixgo-card mb-4">
                        <div class="fixgo-card-header">
                            <h6>
                                <i class="fas fa-wrench me-2 text-primary"></i>
                                {{ $portfolio->title }}
                            </h6>
                            <span class="status-badge badge-accepted">
                                {{ $portfolio->category }}
                            </span>
                        </div>
                        <div class="fixgo-card-body">
                            @if($portfolio->description)
                            <p style="font-size:14px;color:#6b7280;margin-bottom:16px">
                                {{ $portfolio->description }}
                            </p>
                            @endif
                            @if($portfolio->images->count())
                            <div class="row g-2">
                                @foreach($portfolio->images as $image)
                                <div class="col-4 col-md-3">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/'.$image->image_path) }}"
                                             class="w-100 rounded"
                                             style="height:100px;object-fit:cover;cursor:pointer"
                                             onclick="openImageModal('{{ asset('storage/'.$image->image_path) }}')">
                                        @if($image->type !== 'general')
                                        <span style="position:absolute;bottom:4px;left:4px;
                                                     background:rgba(0,0,0,0.7);color:white;
                                                     padding:2px 6px;border-radius:4px;font-size:10px">
                                            {{ ucfirst($image->type) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @if($portfolio->work_date)
                            <p class="text-muted mt-2 mb-0" style="font-size:12px">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($portfolio->work_date)->format('M d, Y') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="fixgo-card">
                    <div class="fixgo-card-body text-center py-5">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No portfolio work added yet.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Reviews Tab --}}
            <div class="tab-pane fade" id="reviews">
                @if($mechanic->ratings->count())
                    @foreach($mechanic->ratings as $review)
                    <div class="fixgo-card mb-3">
                        <div class="fixgo-card-body">
                            <div class="d-flex gap-3">
                                <div class="nav-user-avatar"
                                     style="width:42px;height:42px;flex-shrink:0">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span style="font-weight:600;font-size:14px;color:#1a3c6e">
                                            {{ $review->user->name ?? 'Anonymous' }}
                                        </span>
                                        <span style="font-size:12px;color:#6b7280">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-1 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star
                                               {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"
                                               style="font-size:13px"></i>
                                        @endfor
                                    </div>
                                    @if($review->review)
                                    <p style="font-size:14px;color:#374151;margin:0">
                                        {{ $review->review }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="fixgo-card">
                    <div class="fixgo-card-body text-center py-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reviews yet.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- About Tab --}}
            <div class="tab-pane fade" id="about">
                <div class="fixgo-card">
                    <div class="fixgo-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3" style="background:#f8fafc;border-radius:12px">
                                    <p class="text-muted mb-1" style="font-size:12px;font-weight:600">
                                        FULL NAME
                                    </p>
                                    <p style="font-weight:600;color:#1a3c6e;margin:0">
                                        {{ $mechanic->user->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background:#f8fafc;border-radius:12px">
                                    <p class="text-muted mb-1" style="font-size:12px;font-weight:600">
                                        PHONE
                                    </p>
                                    <p style="font-weight:600;color:#1a3c6e;margin:0">
                                        {{ $mechanic->user->phone ?? 'Not provided' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background:#f8fafc;border-radius:12px">
                                    <p class="text-muted mb-1" style="font-size:12px;font-weight:600">
                                        EXPERIENCE
                                    </p>
                                    <p style="font-weight:600;color:#1a3c6e;margin:0">
                                        {{ $mechanic->years_of_experience ?? 0 }} Years
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background:#f8fafc;border-radius:12px">
                                    <p class="text-muted mb-1" style="font-size:12px;font-weight:600">
                                        LOCATION
                                    </p>
                                    <p style="font-weight:600;color:#1a3c6e;margin:0">
                                        {{ $mechanic->location_address ?? 'Not set' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3" style="background:#f8fafc;border-radius:12px">
                                    <p class="text-muted mb-1" style="font-size:12px;font-weight:600">
                                        BIO
                                    </p>
                                    <p style="color:#374151;margin:0;font-size:14px">
                                        {{ $mechanic->bio ?? 'No bio provided.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background:transparent;border:none">
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" class="img-fluid rounded"
                     style="max-height:80vh">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

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
        if (data.favourited) {
            btn.innerHTML = '<i class="fas fa-heart me-2"></i> Saved to Favourites';
            btn.classList.replace('btn-outline-primary', 'btn-danger');
        } else {
            btn.innerHTML = '<i class="fas fa-heart me-2"></i> Save to Favourites';
            btn.classList.replace('btn-danger', 'btn-outline-primary');
        }
    });
}
</script>
@endpush