@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-users"></i> Users
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-tools"></i> Mechanics
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-clipboard-list"></i> Requests
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-tags"></i> Categories
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-car"></i> Vehicle Types
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-credit-card"></i> Payments
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-chart-bar"></i> Reports
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-star"></i> Reviews & Ratings
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-exclamation-circle"></i> Complaints
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-bullhorn"></i> Announcements
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
    <h3 class="mb-4" style="color:#1a3c6e; font-weight:700">
        Admin Dashboard 🛡️
    </h3>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>1,248</h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-tools"></i></div>
                <div class="stat-info">
                    <h3>342</h3>
                    <p>Total Mechanics</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-info">
                    <h3>856</h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info">
                    <h3>98,320</h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="fixgo-card">
        <div class="fixgo-card-header">
            <h6><i class="fas fa-chart-line me-2 text-primary"></i>Platform Overview</h6>
        </div>
        <div class="fixgo-card-body">
            <p class="text-muted text-center py-3">Charts and analytics coming soon.</p>
        </div>
    </div>
@endsection