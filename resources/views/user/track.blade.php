@extends('layouts.app')

@section('title', 'Track Mechanic')
@section('page-title', 'Track Mechanic')

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

<div class="mb-4">
    <a href="{{ route('user.my-requests') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to My Requests
    </a>
</div>

<div class="row g-4">

    {{-- Map --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    Live Tracking — {{ $request->request_number }}
                </h6>
                <span class="status-badge badge-{{ str_replace('_','-',$request->status) }}">
                    {{ ucwords(str_replace('_',' ',$request->status)) }}
                </span>
            </div>
            <div id="trackingMap"
                 style="height:450px;border-radius:0 0 14px 14px"></div>
        </div>

        {{-- Distance & ETA Bar --}}
        @if(in_array($request->status, ['on_the_way','arrived','repairing']))
        <div class="fixgo-card mt-3">
            <div class="fixgo-card-body">
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div style="font-size:11px;color:#6b7280;font-weight:600;
                                    text-transform:uppercase;letter-spacing:1px">
                            Distance
                        </div>
                        <div style="font-size:20px;font-weight:800;color:#1a3c6e"
                             id="mechanicDistance">
                            <i class="fas fa-spinner fa-spin" style="font-size:14px"></i>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11px;color:#6b7280;font-weight:600;
                                    text-transform:uppercase;letter-spacing:1px">
                            Status
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#f97316"
                             id="liveStatus">
                            {{ ucwords(str_replace('_',' ',$request->status)) }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11px;color:#6b7280;font-weight:600;
                                    text-transform:uppercase;letter-spacing:1px">
                            Last Updated
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#1a3c6e"
                             id="lastUpdated">
                            Just now
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4">

        {{-- Status Timeline --}}
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-list-ol me-2 text-primary"></i>Request Status</h6>
            </div>
            <div class="fixgo-card-body">
                @php
                    $statuses = [
                        'pending'    => ['icon'=>'fas fa-clock','label'=>'Request Pending'],
                        'accepted'   => ['icon'=>'fas fa-check','label'=>'Mechanic Accepted'],
                        'on_the_way' => ['icon'=>'fas fa-car','label'=>'On The Way'],
                        'arrived'    => ['icon'=>'fas fa-map-marker-alt','label'=>'Mechanic Arrived'],
                        'repairing'  => ['icon'=>'fas fa-wrench','label'=>'Repairing Vehicle'],
                        'completed'  => ['icon'=>'fas fa-flag-checkered','label'=>'Job Completed'],
                    ];
                    $statusOrder = array_keys($statuses);
                    $currentIndex = array_search($request->status, $statusOrder);
                @endphp

                <div class="status-timeline">
                    @foreach($statuses as $key => $status)
                    @php
                        $index = array_search($key, $statusOrder);
                        $isDone = $index <= $currentIndex;
                        $isCurrent = $key === $request->status;
                    @endphp
                    <div class="timeline-item {{ $isDone ? 'done' : '' }}
                                              {{ $isCurrent ? 'current' : '' }}">
                        <div class="timeline-icon">
                            <i class="{{ $status['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <span>{{ $status['label'] }}</span>
                            @if($isCurrent)
                            <span class="current-badge">Current</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Mechanic Info --}}
        @if($request->mechanic)
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-user me-2 text-primary"></i>Your Mechanic</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="nav-user-avatar" style="width:50px;height:50px;font-size:20px">
                        @if($request->mechanic->user->profile_photo)
                            <img src="{{ asset('storage/'.$request->mechanic->user->profile_photo) }}"
                                 alt="Mechanic"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div>
                        <p style="font-weight:700;color:#1a3c6e;margin:0;font-size:15px">
                            {{ $request->mechanic->user->name }}
                        </p>
                        <div style="color:#f59e0b;font-size:13px">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $request->mechanic->rating ? '' : 'text-muted' }}"></i>
                            @endfor
                            <span style="color:#6b7280;margin-left:4px">
                                {{ number_format($request->mechanic->rating,1) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Live distance display --}}
                @if(in_array($request->status, ['on_the_way','arrived','repairing']))
                <div class="p-2 mb-3 text-center"
                     style="background:#eff6ff;border-radius:10px">
                    <i class="fas fa-route me-1 text-primary"></i>
                    <span id="mechanicDistanceSidebar"
                          style="font-size:13px;font-weight:600;color:#1a3c6e">
                        Calculating distance...
                    </span>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="tel:{{ $request->mechanic->user->phone }}"
                       class="btn btn-outline-success btn-sm flex-1">
                        <i class="fas fa-phone me-1"></i> Call
                    </a>
                    <a href="{{ route('user.mechanic-profile', $request->mechanic->id) }}"
                       class="btn btn-outline-primary btn-sm flex-1">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Request Details --}}
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Request Details</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label">Service</span>
                    <span class="confirm-value">
                        {{ $request->serviceCategory->name ?? 'N/A' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Request #</span>
                    <span class="confirm-value">{{ $request->request_number }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Problem</span>
                    <span class="confirm-value">
                        {{ \Illuminate\Support\Str::limit($request->problem_description, 40) }}
                    </span>
                </div>
                @if($request->price)
                <div class="confirm-row">
                    <span class="confirm-label">Charge</span>
                    <span class="confirm-value" style="color:#10b981;font-weight:700">
                        KSh {{ number_format($request->price) }}
                    </span>
                </div>
                @endif
                <div class="confirm-row">
                    <span class="confirm-label">Date</span>
                    <span class="confirm-value">
                        {{ $request->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.status-timeline { display: flex; flex-direction: column; gap: 0; }
.timeline-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-left: 2px solid #e5e7eb;
    margin-left: 16px;
    padding-left: 20px;
    position: relative;
    color: #9ca3af;
}
.timeline-item:last-child { border-left: none; }
.timeline-icon {
    position: absolute;
    left: -14px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}
.timeline-item.done .timeline-icon {
    background: #10b981;
    color: white;
}
.timeline-item.done {
    color: #374151;
    border-left-color: #10b981;
}
.timeline-item.current .timeline-icon {
    background: linear-gradient(135deg,#1a3c6e,#3b82f6);
    color: white;
}
.timeline-content {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
}
.current-badge {
    background: #eff6ff;
    color: #1a3c6e;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 700;
}
.confirm-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f4ff;
    font-size: 13px;
}
.confirm-row:last-child { border-bottom: none; }
.confirm-label { color: #6b7280; }
.confirm-value { font-weight: 600; color: #1a3c6e; text-align: right; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const userLat  = {{ $request->user_latitude }};
const userLng  = {{ $request->user_longitude }};
const mechanicId = {{ $request->mechanic_id ?? 'null' }};

// Initial mechanic position
let mechLat = {{ $request->mechanic->latitude ?? $request->user_latitude }};
let mechLng = {{ $request->mechanic->longitude ?? $request->user_longitude }};

// Init map
const map = L.map('trackingMap').setView([userLat, userLng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Custom icons
const userIcon = L.divIcon({
    html: `<div style="background:#3b82f6;width:40px;height:40px;border-radius:50%;
                       display:flex;align-items:center;justify-content:center;
                       border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)">
               <i class="fas fa-user" style="color:white;font-size:16px"></i>
           </div>`,
    className: '',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
});

const mechanicIcon = L.divIcon({
    html: `<div style="background:#f97316;width:40px;height:40px;border-radius:50%;
                       display:flex;align-items:center;justify-content:center;
                       border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)">
               <i class="fas fa-wrench" style="color:white;font-size:16px"></i>
           </div>`,
    className: '',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
});

// Add user marker
L.marker([userLat, userLng], { icon: userIcon })
    .addTo(map)
    .bindPopup('<b>Your Location</b>')
    .openPopup();

@if(in_array($request->status, ['on_the_way','arrived','repairing']))

// Add mechanic marker
let mechanicMarker = L.marker([mechLat, mechLng], { icon: mechanicIcon })
    .addTo(map)
    .bindPopup('<b>{{ $request->mechanic->user->name ?? "Mechanic" }}</b><br>Your Mechanic');

// Draw dashed route line
let routeLine = L.polyline([[userLat, userLng], [mechLat, mechLng]], {
    color: '#3b82f6',
    weight: 3,
    dashArray: '8, 8',
    opacity: 0.8
}).addTo(map);

// Fit map to show both markers
map.fitBounds([
    [userLat, userLng],
    [mechLat, mechLng]
], { padding: [50, 50] });

// Calculate distance in km using Haversine formula
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

// Update distance display
function updateDistanceDisplay(lat, lng) {
    const dist = calculateDistance(userLat, userLng, lat, lng);
    const distText = dist < 1
        ? (dist * 1000).toFixed(0) + ' m away'
        : dist.toFixed(1) + ' km away';

    const mainDisplay = document.getElementById('mechanicDistance');
    const sideDisplay = document.getElementById('mechanicDistanceSidebar');

    if (mainDisplay) mainDisplay.textContent = distText;
    if (sideDisplay) sideDisplay.textContent = distText;
}

// Initial distance
updateDistanceDisplay(mechLat, mechLng);

// Poll mechanic location every 10 seconds
setInterval(function() {
    if (!mechanicId) return;

    fetch(`/user/mechanic-location/${mechanicId}`)
        .then(r => r.json())
        .then(data => {
            if (data.latitude && data.longitude) {
                const newLat = parseFloat(data.latitude);
                const newLng = parseFloat(data.longitude);

                // Smoothly move mechanic marker
                mechanicMarker.setLatLng([newLat, newLng]);

                // Update route line
                routeLine.setLatLngs([
                    [userLat, userLng],
                    [newLat, newLng]
                ]);

                // Update distance
                updateDistanceDisplay(newLat, newLng);

                // Update last updated time
                const now = new Date();
                const timeStr = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const lastUpdated = document.getElementById('lastUpdated');
                if (lastUpdated) lastUpdated.textContent = timeStr;
            }
        })
        .catch(err => console.log('Location poll error:', err));
}, 10000);

@else

// For completed/pending - just show both static markers if mechanic exists
@if($request->mechanic && $request->mechanic->latitude)
L.marker([mechLat, mechLng], { icon: mechanicIcon })
    .addTo(map)
    .bindPopup('<b>{{ $request->mechanic->user->name ?? "Mechanic" }}</b>');

L.polyline([[userLat, userLng], [mechLat, mechLng]], {
    color: '#d1d5db',
    weight: 2,
    dashArray: '6, 6'
}).addTo(map);

map.fitBounds([
    [userLat, userLng],
    [mechLat, mechLng]
], { padding: [50, 50] });
@endif

@endif
</script>
@endpush