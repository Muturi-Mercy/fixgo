<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Dashboard
</a>
<a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
    <i class="fas fa-users"></i> Drivers
</a>
<a href="{{ route('admin.mechanics') }}" class="nav-link {{ request()->routeIs('admin.mechanics*') ? 'active' : '' }}">
    <i class="fas fa-tools"></i> Mechanics
    @if($pendingMechanicsCount ?? 0)
        <span class="nav-badge">{{ $pendingMechanicsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.requests') }}" class="nav-link {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
    <i class="fas fa-clipboard-list"></i> Requests
</a>
<a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
    <i class="fas fa-tags"></i> Categories
</a>
<a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i> Reports
</a>
<a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
    <i class="fas fa-star"></i> Reviews & Ratings
</a>
<a href="{{ route('admin.announcements') }}" class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
    <i class="fas fa-bullhorn"></i> Announcements
</a>
<a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Settings
</a>
<a href="{{ route('logout') }}" class="nav-link"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>