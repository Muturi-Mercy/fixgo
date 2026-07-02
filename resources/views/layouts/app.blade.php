<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class="sidebar-sos">
            <p class="sos-text">Need urgent help?<br>
                <small>Tap SOS for immediate assistance</small>
            </p>
            <button class="btn-sos">
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
                                     alt="Profile">
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

</body>
</html>
