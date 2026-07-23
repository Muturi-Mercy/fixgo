@extends('layouts.app')

@section('title', 'Request Details')
@section('page-title', 'Request Details')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('user.request-assistance') }}" class="nav-link">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="{{ route('user.my-requests') }}" class="nav-link active">
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('user.my-requests') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to My Requests
    </a>
    <span class="status-badge badge-{{ str_replace('_','-',$request->status) }}"
          style="font-size:13px;padding:8px 16px">
        {{ ucwords(str_replace('_',' ',$request->status)) }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Left Column --}}
    <div class="col-lg-4">

        {{-- Request Info --}}
        <div class="fixgo-card mb-4">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:24px 20px;border-radius:14px 14px 0 0;text-align:center">
                <div style="width:60px;height:60px;background:rgba(255,255,255,0.2);
                            border-radius:16px;margin:0 auto 12px;
                            display:flex;align-items:center;justify-content:center">
                    <i class="{{ getServiceIcon($request->serviceCategory->name ?? '') }}"
                       style="font-size:26px;color:white"></i>
                </div>
                <h5 style="color:white;font-weight:700;margin:0">
                    {{ $request->serviceCategory->name ?? 'N/A' }}
                </h5>
                <p style="color:rgba(255,255,255,0.7);margin:4px 0 0;font-size:13px">
                    {{ $request->request_number }}
                </p>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label"><i class="fas fa-car me-1 text-primary"></i> Vehicle</span>
                    <span class="confirm-value">{{ $request->vehicleCategory->name ?? 'N/A' }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label"><i class="fas fa-info me-1 text-primary"></i> Status</span>
                    <span class="confirm-value">
                        <span class="status-badge badge-{{ str_replace('_','-',$request->status) }}">
                            {{ ucwords(str_replace('_',' ',$request->status)) }}
                        </span>
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label"><i class="fas fa-tag me-1 text-primary"></i> Charge</span>
                    <span class="confirm-value" style="color:#10b981;font-weight:700">
                        {{ $request->price ? 'KSh '.number_format($request->price) : 'Not set' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label"><i class="fas fa-calendar me-1 text-primary"></i> Date</span>
                    <span class="confirm-value">{{ $request->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label"><i class="fas fa-map-marker-alt me-1 text-danger"></i> Location</span>
                    <span class="confirm-value" style="max-width:180px;text-align:right">
                        {{ $request->user_address ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Problem Description --}}
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-comment-alt me-2 text-primary"></i>Problem</h6>
            </div>
            <div class="fixgo-card-body">
                <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">
                    {{ $request->problem_description }}
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-column gap-2">
            {{-- @if(in_array($request->status, ['accepted','on_the_way','arrived','repairing']))
            <a href="{{ route('user.track', $request->id) }}"
               class="btn btn-fixgo w-100">
                <span style="color:white"><i class="fas fa-map-marker-alt me-2"></i> Track Mechanic</span>
            </a>
            @endif --}}
           @if(in_array($request->status, ['pending', 'accepted']))
            <form method="POST"
                action="{{ route('user.cancel-request', $request->id) }}"
                onsubmit="return confirm('Cancel this request?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="fas fa-times me-2"></i> Cancel Request
                </button>
            </form>
            @endif
            @if($request->status === 'completed' && !$request->rating)
            <button class="btn btn-warning w-100" style="color:white"
                    onclick="showRateModal({{ $request->id }})">
                <i class="fas fa-star me-2"></i> Rate Mechanic
            </button>
            @endif
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-8">

        {{-- Mechanic Info --}}
        @if($request->mechanic)
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-tools me-2 text-primary"></i>Your Mechanic</h6>
                <a href="{{ route('user.mechanic-profile', $request->mechanic->id) }}"
                   class="btn btn-sm btn-outline-primary"
                   style="padding:4px 10px;font-size:12px">
                    View Profile
                </a>
            </div>
            <div class="fixgo-card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="nav-user-avatar"
                         style="width:56px;height:56px;font-size:22px;flex-shrink:0">
                        @if($request->mechanic->user->profile_photo)
                            <img src="{{ asset('storage/'.$request->mechanic->user->profile_photo) }}"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h6 style="font-weight:700;color:#1a3c6e;margin:0">
                            {{ $request->mechanic->user->name }}
                        </h6>
                        <div style="color:#f59e0b;margin:4px 0">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $request->mechanic->rating ? '' : 'text-muted' }}"
                                   style="font-size:13px"></i>
                            @endfor
                            <span style="font-size:12px;color:#6b7280;margin-left:4px">
                                {{ number_format($request->mechanic->rating,1) }}
                            </span>
                        </div>
                        <p class="text-muted mb-0" style="font-size:13px">
                            <i class="fas fa-phone me-1"></i>
                            {{ $request->mechanic->user->phone ?? 'N/A' }}
                        </p>
                    </div>
                    @if($request->mechanic->user->phone)
                    <a href="tel:{{ $request->mechanic->user->phone }}"
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-1"></i> <span style="color:black">Call</span>
                    </a>
                    <a href="{{ route('user.chat', $request->id) }}"
                    class="btn btn-outline-success btn-sm">
                        <span style="color:#3b82f6"><i class="fas fa-comment me-2"></i></span><span style="color:black">Chat</span>
                    </a>
                    @endif
                    @if(in_array($request->status, ['accepted','on_the_way','arrived','repairing']))
                    <a href="{{ route('user.chat', $request->id) }}"
                    class="btn btn-outline-success btn-sm">
                        <span style="color:#3b82f6"><i class="fas fa-comment me-2"></i></span><span style="color:black">Chat</span>
                    </a>
                    <a href="{{ route('user.track', $request->id) }}"
                        class="btn btn-outline-success btn-sm">
                            <span style="color:red"><i class="fas fa-map-marker-alt me-2"></span></i><span style="color:black">Track Mechanic</span> 
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-body text-center py-4">
                <i class="fas fa-hourglass-half fa-2x text-muted mb-3"></i>
                <p class="text-muted mb-0">Waiting for a mechanic to accept your request.</p>
            </div>
        </div>
        @endif

        {{-- Map --}}
        @if($request->user_latitude && $request->user_longitude)
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-map-marker-alt me-2 text-danger"></i>Request Location</h6>
            </div>
            <div id="requestMap"
                 style="height:250px;border-radius:0 0 14px 14px"></div>
        </div>
        @endif

        {{-- Vehicle Photos --}}
        @if($request->photos->count())
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-camera me-2 text-primary"></i>Vehicle Photos</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="row g-2">
                    @foreach($request->photos as $photo)
                    <div class="col-4 col-md-3">
                        <img src="{{ asset('storage/'.$photo->photo_path) }}"
                             class="w-100 rounded"
                             style="height:100px;object-fit:cover;cursor:pointer"
                             onclick="openLightbox('{{ asset('storage/'.$photo->photo_path) }}')">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Review --}}
        @if($request->rating)
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-star me-2 text-warning"></i>Your Review</h6>
            </div>
            <div class="fixgo-card-body">
                <div style="color:#f59e0b;margin-bottom:8px">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star
                           {{ $i <= $request->rating->rating ? '' : 'text-muted' }}"
                           style="font-size:18px"></i>
                    @endfor
                    <span style="font-size:14px;color:#6b7280;margin-left:8px">
                        {{ $request->rating->rating }}/5
                    </span>
                </div>
                @if($request->rating->review)
                <p style="font-size:14px;color:#374151;padding:12px 16px;
                          background:#f8fafc;border-radius:10px;
                          border-left:3px solid #3b82f6;margin:0">
                    "{{ $request->rating->review }}"
                </p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Rate Modal --}}
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i> Rate Your Mechanic
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('user.rate-request') }}">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">How was your experience?</p>
                        <div class="star-rating" id="starRating">
                            @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star star" data-value="{{ $i }}"
                               style="font-size:32px;color:#d1d5db;cursor:pointer"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Leave a Review</label>
                        <textarea name="review" class="form-control" rows="3"
                                  placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-fixgo w-100">
                        <i class="fas fa-paper-plane me-2"></i> Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content"
             style="background:rgba(0,0,0,0.9);border:none;border-radius:16px">
            <div class="modal-body p-2 text-center">
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        style="position:absolute;top:12px;right:12px"></button>
                <img id="lightboxImage" src="" class="img-fluid"
                     style="max-height:85vh;border-radius:12px">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.confirm-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #f0f4ff;
    font-size: 13px;
    gap: 10px;
}
.confirm-row:last-child { border-bottom: none; }
.confirm-label { color: #6b7280; flex-shrink: 0; }
.confirm-value { font-weight: 600; color: #1a3c6e; text-align: right; }
.star-rating .star:hover,
.star-rating .star.active { color: #f59e0b !important; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($request->user_latitude && $request->user_longitude)
const map = L.map('requestMap').setView(
    [{{ $request->user_latitude }}, {{ $request->user_longitude }}], 14
);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

const userIcon = L.divIcon({
    html: `<div style="background:#3b82f6;width:36px;height:36px;border-radius:50%;
                       display:flex;align-items:center;justify-content:center;
                       border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)">
               <i class="fas fa-user" style="color:white;font-size:14px"></i>
           </div>`,
    className: '',
    iconSize: [36, 36],
    iconAnchor: [18, 18],
});

L.marker(
    [{{ $request->user_latitude }}, {{ $request->user_longitude }}],
    { icon: userIcon }
).addTo(map).bindPopup('Your Location').openPopup();
@endif

function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}

function showRateModal(id) {
    new bootstrap.Modal(document.getElementById('rateModal')).show();
}

// Star rating
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('ratingValue').value = value;
        document.querySelectorAll('.star').forEach(s => {
            s.classList.toggle('active', s.getAttribute('data-value') <= value);
            s.style.color = s.getAttribute('data-value') <= value ? '#f59e0b' : '#d1d5db';
        });
    });
});
</script>
@endpush