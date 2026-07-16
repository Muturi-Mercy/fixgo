<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FixGo – @yield('title', 'Smart Vehicle Breakdown Assistance')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <link rel="shortcut icon" href="{{ asset('images/driverlog.png') }}" >
</head>
<body>

<div class="fixgo-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="{{ asset('images/driverlog.png') }}" alt="Driver Logo"  style="width:60px; height:60px; object-fit:contain;">
            </div>
            <div>
                <h1>FixGo</h1>
                <p>Fix Smart. Go Safe.</p>
            </div>
        </div>

        {{-- Role Badge --}}
        <div class="sidebar-role-badge">
            @if(auth()->user()->role === 'admin')
                <i class="fas fa-shield-alt me-2"></i> ADMIN PANEL
            @elseif(auth()->user()->role === 'mechanic')
                <i class="fas fa-tools me-2"></i> MECHANIC PANEL
            @else
                <i class="fas fa-car me-2"></i> DRIVER PANEL
            @endif
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            @yield('sidebar-menu')
        </nav>

        {{-- SOS Button (User only) --}}
        @if(auth()->user()->role === 'user')
        <div class="sidebar-sos" style="padding-top: 0px; padding-bottom:10px">
            <p class="sos-text" style="color: black">Need urgent help?<br>
                <small>Tap SOS for immediate assistance</small>
            </p>
            <button class="btn-sos" onclick="triggerSOS()">
                <i class="fas fa-exclamation-triangle me-2"></i> SOS
            </button>
        </div>
        @endif

    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content" id="mainContent">

        {{-- TOP NAVBAR --}}
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="page-title mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="navbar-right">
                {{-- Notifications --}}
                <div class="nav-icon-btn position-relative">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>

                {{-- User Info --}}
                <div class="dropdown">
                    <div class="nav-user" data-bs-toggle="dropdown" style="cursor:pointer">
                        <div class="nav-user-avatar">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    alt="Profile"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="nav-user-info d-none d-md-block">
                            <span class="nav-user-name">{{ auth()->user()->name }}</span>
                            <span class="nav-user-role">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <i class="fas fa-chevron-down ms-2 text-muted" style="font-size:12px"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2 text-primary"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2 text-primary"></i> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </nav>

        {{-- PAGE CONTENT --}}
        <div class="page-content">
            @yield('content')
        </div>

    </div>
</div>

@stack('scripts')

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
    }

    // Set active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
</script>

{{-- SOS Modal --}}
@if(auth()->user()->role === 'user')
<div class="modal fade" id="sosModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:3px solid #ef4444">
            <div class="modal-body p-0">

                {{-- SOS Header --}}
                <div style="background:linear-gradient(135deg,#dc2626,#ef4444);
                            padding:30px;text-align:center;border-radius:17px 17px 0 0">
                    <div style="width:80px;height:80px;background:rgba(255,255,255,0.2);
                                border-radius:50%;margin:0 auto 16px;display:flex;
                                align-items:center;justify-content:center">
                        <i class="fas fa-exclamation-triangle"
                           style="font-size:36px;color:white"></i>
                    </div>
                    <h4 style="color:white;font-weight:800;margin:0">EMERGENCY SOS</h4>
                    <p style="color:rgba(255,255,255,0.8);margin:6px 0 0;font-size:14px">
                        We'll find the nearest mechanic immediately
                    </p>
                </div>

                <div class="p-4">

                    {{-- Location Status --}}
                    <div class="d-flex align-items-center gap-3 p-3 mb-3"
                         style="background:#fef2f2;border-radius:12px;border:1px solid #fecaca">
                        <i class="fas fa-map-marker-alt text-danger fa-lg"></i>
                        <div>
                            <p style="font-weight:600;color:#dc2626;margin:0;font-size:14px">
                                Your Location
                            </p>
                            <p id="sosLocationText"
                               style="color:#6b7280;font-size:12px;margin:0">
                                Getting your location...
                            </p>
                        </div>
                        <button onclick="getSOSLocation()"
                                class="btn btn-sm btn-outline-danger ms-auto">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                    </div>

                    {{-- Problem Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Describe the Emergency (Optional)
                        </label>
                        <textarea id="sosProblem" class="form-control" rows="2"
                                  placeholder="e.g. Car won't start on Ngong Road..."></textarea>
                    </div>

                    <input type="hidden" id="sosLat">
                    <input type="hidden" id="sosLng">

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2">
                        <button onclick="submitSOS()"
                                class="btn flex-1"
                                style="background:#dc2626;color:white;
                                       padding:14px;font-weight:700;
                                       border-radius:12px;font-size:15px">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send SOS Now
                        </button>
                        <button data-bs-dismiss="modal"
                                class="btn btn-outline-secondary"
                                style="padding:14px 20px;border-radius:12px">
                            Cancel
                        </button>
                    </div>

                    {{-- Emergency Contacts --}}
                    <div class="mt-3 p-3"
                         style="background:#f8fafc;border-radius:12px">
                        <p style="font-size:12px;font-weight:700;
                                  color:#374151;margin-bottom:8px">
                            EMERGENCY CONTACTS
                        </p>
                        <div class="d-flex gap-3">
                            <a href="tel:999"
                               class="btn btn-sm btn-outline-danger flex-1">
                                <i class="fas fa-phone me-1"></i> Police 999
                            </a>
                            <a href="tel:911"
                               class="btn btn-sm btn-outline-warning flex-1">
                                <i class="fas fa-ambulance me-1"></i> Ambulance 911
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function triggerSOS() {
    const modal = new bootstrap.Modal(document.getElementById('sosModal'));
    modal.show();
    getSOSLocation();
}

function getSOSLocation() {
    const text = document.getElementById('sosLocationText');
    text.textContent = 'Getting your location...';
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                document.getElementById('sosLat').value = lat;
                document.getElementById('sosLng').value = lng;
                // Reverse geocode
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                    .then(r => r.json())
                    .then(data => {
                        text.textContent = data.display_name ?? `${lat}, ${lng}`;
                    });
            },
            function() {
                text.textContent = 'Could not get location. Please enter manually.';
            }
        );
    }
}

function submitSOS() {
    const lat = document.getElementById('sosLat').value;
    const lng = document.getElementById('sosLng').value;
    const problem = document.getElementById('sosProblem').value;

    if (!lat || !lng) {
        alert('Please allow location access first.');
        return;
    }

    fetch('{{ route("user.sos") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lat, lng, problem })
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('sosModal')).hide();
        showSOSSuccess(data.request_number);
    })
    .catch(() => {
        alert('SOS sent! We are finding the nearest mechanic.');
    });
}

function showSOSSuccess(requestNumber) {
    const div = document.createElement('div');
    div.innerHTML = `
        <div style="position:fixed;top:20px;right:20px;z-index:9999;
                    background:#10b981;color:white;padding:16px 24px;
                    border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,0.2);
                    max-width:320px">
            <div style="font-weight:700;font-size:15px;margin-bottom:4px">
                <i class="fas fa-check-circle me-2"></i> SOS Sent Successfully!
            </div>
            <div style="font-size:13px;opacity:0.9">
                Request ${requestNumber} created. Finding nearest mechanic...
            </div>
        </div>
    `;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 5000);
}
</script>
@endif

</body>
</html>
