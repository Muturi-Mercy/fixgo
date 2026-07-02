@extends('layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-list"></i> My Requests
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-map-marker-alt"></i> Track Mechanic
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-heart"></i> Favourites
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-wallet"></i> Wallet
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-bell"></i> Notifications
        <span class="nav-badge">3</span>
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
        Welcome back, {{ auth()->user()->name }}! 
    </h3> --}}

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-list"></i></div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-spinner"></i></div>
                <div class="stat-info">
                    <h3>1</h3>
                    <p>Active Requests</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>7</h3>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-heart"></i></div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Favourites</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Requests --}}
    <div class="fixgo-card">
        <div class="fixgo-card-header">
            <h6><i class="fas fa-history me-2 text-primary"></i>Recent Requests</h6>
            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="fixgo-card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:13px; color:#6b7280; font-weight:600; padding:12px 20px">Request</th>
                        <th style="font-size:13px; color:#6b7280; font-weight:600">Status</th>
                        <th style="font-size:13px; color:#6b7280; font-weight:600">Date</th>
                        <th style="font-size:13px; color:#6b7280; font-weight:600">Amount</th>
                        <th style="font-size:13px; color:#6b7280; font-weight:600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600; font-size:14px">Oil Change</div>
                            <div style="font-size:12px; color:#6b7280">REQ#1187</div>
                        </td>
                        <td><span class="status-badge badge-completed">Completed</span></td>
                        <td style="font-size:13px; color:#6b7280">May 16, 2025</td>
                        <td style="font-weight:600; color:#1a3c6e">KSh 1,500</td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600; font-size:14px">Brake Repair</div>
                            <div style="font-size:12px; color:#6b7280">REQ#1122</div>
                        </td>
                        <td><span class="status-badge badge-completed">Completed</span></td>
                        <td style="font-size:13px; color:#6b7280">May 02, 2025</td>
                        <td style="font-weight:600; color:#1a3c6e">KSh 2,000</td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600; font-size:14px">Tyre Change</div>
                            <div style="font-size:12px; color:#6b7280">REQ#1098</div>
                        </td>
                        <td><span class="status-badge badge-completed">Completed</span></td>
                        <td style="font-size:13px; color:#6b7280">Apr 20, 2025</td>
                        <td style="font-weight:600; color:#1a3c6e">KSh 800</td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection