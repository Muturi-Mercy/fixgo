@extends('layouts.app')

@section('title', 'Mechanic Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-bell"></i> Service Requests
        <span class="nav-badge nav-badge-orange">2</span>
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-toggle-on"></i> Availability
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-star"></i> Reviews
    </a>
    <a href="#" class="nav-link">
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
    {{-- <h3 class="mb-4" style="color:#1a3c6e; font-weight:700">
        Welcome, {{ auth()->user()->name }}! 
    </h3> --}}

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Today's Jobs</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
                <div class="stat-info">
                    <h3>156</h3>
                    <p>Completed Jobs</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-coins"></i></div>
                <div class="stat-info">
                    <h3>4,500</h3>
                    <p>Today's Earnings</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-wallet"></i></div>
                <div class="stat-info">
                    <h3>78,300</h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Service Requests --}}
    <div class="fixgo-card">
        <div class="fixgo-card-header">
            <h6><i class="fas fa-bell me-2 text-primary"></i>Incoming Service Requests</h6>
            <span class="status-badge badge-pending">2 New</span>
        </div>
        <div class="fixgo-card-body">
            <p class="text-muted text-center py-3">No pending requests at the moment.</p>
        </div>
    </div>
@endsection