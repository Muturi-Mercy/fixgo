@extends('layouts.app')

@section('title', 'Request Details')
@section('page-title', 'Request Details')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.requests') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Requests
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

        {{-- Request Info Card --}}
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
                    <span class="confirm-label">
                        <i class="fas fa-car me-1 text-primary"></i> Vehicle
                    </span>
                    <span class="confirm-value">
                        {{ $request->vehicleCategory->name ?? 'N/A' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-info me-1 text-primary"></i> Status
                    </span>
                    <span class="confirm-value">
                        <span class="status-badge badge-{{ str_replace('_','-',$request->status) }}">
                            {{ ucwords(str_replace('_',' ',$request->status)) }}
                        </span>
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-tag me-1 text-primary"></i> Charge
                    </span>
                    <span class="confirm-value" style="color:#10b981;font-weight:700">
                        {{ $request->price ? 'KSh '.number_format($request->price) : 'Not set' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-calendar me-1 text-primary"></i> Date
                    </span>
                    <span class="confirm-value">
                        {{ $request->created_at->format('M d, Y h:i A') }}
                    </span>
                </div>
                @if($request->completed_at)
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-flag-checkered me-1 text-primary"></i> Completed
                    </span>
                    <span class="confirm-value">
                        {{ \Carbon\Carbon::parse($request->completed_at)->format('M d, Y h:i A') }}
                    </span>
                </div>
                @endif
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> Location
                    </span>
                    <span class="confirm-value" style="text-align:right;max-width:180px">
                        {{ $request->user_address ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Problem Description --}}
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-comment-alt me-2 text-primary"></i>
                    Problem Description
                </h6>
            </div>
            <div class="fixgo-card-body">
                <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">
                    {{ $request->problem_description }}
                </p>
            </div>
        </div>

        {{-- Vehicle Photos --}}
        @if($request->photos->count())
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-camera me-2 text-primary"></i>
                    Vehicle Photos
                </h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $request->photos->count() }} photos
                </span>
            </div>
            <div class="fixgo-card-body">
                <div class="row g-2">
                    @foreach($request->photos as $photo)
                    <div class="col-6">
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

    </div>

    {{-- Right Column --}}
    <div class="col-lg-8">

        {{-- User & Mechanic Cards --}}
        <div class="row g-3 mb-4">

            {{-- User --}}
            <div class="col-md-6">
                <div class="fixgo-card h-100">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-user me-2 text-primary"></i>Driver</h6>
                        <a href="{{ route('admin.users.view', $request->user_id) }}"
                           class="btn btn-sm btn-outline-primary"
                           style="padding:4px 10px;font-size:11px">
                            View Profile
                        </a>
                    </div>
                    <div class="fixgo-card-body text-center">
                        <div class="nav-user-avatar mx-auto mb-3"
                             style="width:56px;height:56px;font-size:22px">
                            @if($request->user->profile_photo)
                                <img src="{{ asset('storage/'.$request->user->profile_photo) }}"
                                     style="width:100%;height:100%;
                                            object-fit:cover;border-radius:50%">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <p style="font-weight:700;color:#1a3c6e;margin:0;font-size:15px">
                            {{ $request->user->name }}
                        </p>
                        <p class="text-muted mb-1" style="font-size:13px">
                            {{ $request->user->email }}
                        </p>
                        <p class="text-muted mb-3" style="font-size:13px">
                            <i class="fas fa-phone me-1"></i>
                            {{ $request->user->phone ?? 'N/A' }}
                        </p>
                        @if($request->user->phone)
                        <a href="tel:{{ $request->user->phone }}"
                           class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-phone me-1"></i> Call Driver
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Mechanic --}}
            <div class="col-md-6">
                <div class="fixgo-card h-100">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-tools me-2 text-primary"></i>Mechanic</h6>
                        @if($request->mechanic)
                        <a href="{{ route('admin.mechanics.view', $request->mechanic->id) }}"
                           class="btn btn-sm btn-outline-primary"
                           style="padding:4px 10px;font-size:11px">
                            View Profile
                        </a>
                        @endif
                    </div>
                    <div class="fixgo-card-body text-center">
                        @if($request->mechanic)
                        <div class="nav-user-avatar mx-auto mb-3"
                             style="width:56px;height:56px;font-size:22px">
                            @if($request->mechanic->user->profile_photo)
                                <img src="{{ asset('storage/'.$request->mechanic->user->profile_photo) }}"
                                     style="width:100%;height:100%;
                                            object-fit:cover;border-radius:50%">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <p style="font-weight:700;color:#1a3c6e;margin:0;font-size:15px">
                            {{ $request->mechanic->user->name }}
                        </p>
                        <p class="text-muted mb-1" style="font-size:13px">
                            {{ $request->mechanic->user->email }}
                        </p>
                        <div style="color:#f59e0b;margin-bottom:12px">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $request->mechanic->rating ? '' : 'text-muted' }}"
                                   style="font-size:13px"></i>
                            @endfor
                            <span style="font-size:12px;color:#6b7280;margin-left:4px">
                                {{ number_format($request->mechanic->rating,1) }}
                            </span>
                        </div>
                        @if($request->mechanic->user->phone)
                        <a href="tel:{{ $request->mechanic->user->phone }}"
                           class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-phone me-1"></i> Call Mechanic
                        </a>
                        @endif
                        @else
                        <div class="py-3">
                            <i class="fas fa-hourglass-half fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No mechanic assigned yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Map --}}
        @if($request->user_latitude && $request->user_longitude)
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    Request Location
                </h6>
            </div>
            <div id="adminRequestMap"
                 style="height:280px;border-radius:0 0 14px 14px"></div>
        </div>
        @endif

        {{-- Rating --}}
        @if($request->rating)
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-star me-2 text-warning"></i>Customer Review</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="d-flex gap-3">
                    <div class="nav-user-avatar"
                         style="width:44px;height:44px;font-size:18px;flex-shrink:0">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p style="font-weight:700;color:#1a3c6e;margin:0;font-size:14px">
                            {{ $request->user->name }}
                        </p>
                        <div style="color:#f59e0b;margin:4px 0">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $request->rating->rating ? '' : 'text-muted' }}"></i>
                            @endfor
                            <span style="font-size:13px;color:#6b7280;margin-left:6px">
                                {{ $request->rating->rating }}/5
                            </span>
                        </div>
                        @if($request->rating->review)
                        <p style="font-size:14px;color:#374151;margin:0;
                                  padding:10px 14px;background:#f8fafc;
                                  border-radius:10px;border-left:3px solid #3b82f6">
                            "{{ $request->rating->review }}"
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Chat History --}}
        {{-- @if($request->chat->count())
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-comments me-2 text-primary"></i>
                    Chat History
                </h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $request->chat->count() }} messages
                </span>
            </div>
            <div class="fixgo-card-body"
                 style="max-height:300px;overflow-y:auto;
                        display:flex;flex-direction:column;gap:10px">
                @foreach($request->chat as $msg)
                <div class="d-flex gap-2
                     {{ $msg->sender_id === $request->user_id ? '' : 'flex-row-reverse' }}">
                    <div class="nav-user-avatar"
                         style="width:32px;height:32px;font-size:13px;flex-shrink:0">
                        <i class="fas fa-user"></i>
                    </div>
                    <div style="max-width:70%">
                        <p style="font-size:11px;color:#9ca3af;margin-bottom:3px;
                                  {{ $msg->sender_id === $request->user_id ? 'text-left' : 'text-right' }}">
                            {{ $msg->sender->name ?? 'N/A' }}
                        </p>
                        <div style="padding:8px 14px;border-radius:14px;font-size:13px;
                                    background:{{ $msg->sender_id === $request->user_id ? '#f0f4ff' : 'linear-gradient(135deg,#1a3c6e,#3b82f6)' }};
                                    color:{{ $msg->sender_id === $request->user_id ? '#1a3c6e' : 'white' }}">
                            {{ $msg->message }}
                        </div>
                        <p style="font-size:10px;color:#9ca3af;margin-top:3px;
                                  {{ $msg->sender_id === $request->user_id ? 'text-left' : 'text-right' }}">
                            {{ $msg->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif --}}

    </div>
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content"
             style="background:rgba(0,0,0,0.9);border:none;border-radius:16px">
            <div class="modal-body p-2 text-center position-relative">
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        style="position:absolute;top:12px;right:12px;z-index:10">
                </button>
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
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($request->user_latitude && $request->user_longitude)
const map = L.map('adminRequestMap').setView(
    [{{ $request->user_latitude }}, {{ $request->user_longitude }}], 14
);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// User marker
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
).addTo(map).bindPopup('<b>{{ $request->user->name }}</b><br>Request Location').openPopup();

@if($request->mechanic && $request->mechanic->latitude)
const mechanicIcon = L.divIcon({
    html: `<div style="background:#f97316;width:36px;height:36px;border-radius:50%;
                       display:flex;align-items:center;justify-content:center;
                       border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)">
               <i class="fas fa-wrench" style="color:white;font-size:14px"></i>
           </div>`,
    className: '',
    iconSize: [36, 36],
    iconAnchor: [18, 18],
});

L.marker(
    [{{ $request->mechanic->latitude }}, {{ $request->mechanic->longitude }}],
    { icon: mechanicIcon }
).addTo(map).bindPopup('<b>{{ $request->mechanic->user->name }}</b><br>Mechanic Location');

L.polyline([
    [{{ $request->user_latitude }}, {{ $request->user_longitude }}],
    [{{ $request->mechanic->latitude }}, {{ $request->mechanic->longitude }}]
], { color:'#3b82f6', weight:2, dashArray:'6,6' }).addTo(map);

map.fitBounds([
    [{{ $request->user_latitude }}, {{ $request->user_longitude }}],
    [{{ $request->mechanic->latitude }}, {{ $request->mechanic->longitude }}]
], { padding: [30, 30] });
@endif
@endif

function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>
@endpush