@extends('layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('user.request-assistance') }}" class="nav-link">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="{{ route('user.my-requests') }}" class="nav-link">
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
        <span class="nav-badge">3</span>
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
        <div>
            {{-- <h4 style="color:#1a3c6e; font-weight:700; margin:0">
                Welcome back, {{ auth()->user()->name }}! 
            </h4>style="font-size:14px" --}}
            <p class="text-muted mb-0" style="color:#1a3c6e; font-weight:700; margin:0">
                Here's what's happening with your requests today.
            </p>
        </div>

        <a href="{{ route('user.request-assistance') }}" class="btn btn-fixgo"
        style="width:auto; padding:10px 20px">
            <i class="fas fa-plus me-2" style="color: white"></i> <span style="color: white">New Request</span>
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-list"></i></div>
                <div class="stat-info">
                    <h3>{{ $totalRequests }}</h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-spinner"></i></div>
                <div class="stat-info">
                    <h3>{{ $activeRequests }}</h3>
                    <p>Active Request</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>{{ $completedRequests }}</h3>
                    <p>Completed Request</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-heart"></i></div>
                <div class="stat-info">
                    <h3>{{ $favourites }}</h3>
                    <p>Favourites</p>
                </div>
            </div>
        </div>

    </div>

   
    <div class="row g-4">

        {{-- Active Request --}}
        <div class="col-md-6">
            <div class="fixgo-card h-100">
                <div class="fixgo-card-header">
                     <h6>{{--<i class="fas fa-bolt me-2 text-warning"></i> --}}Active Request</h6>
                    @if($activeRequest)
                        <span class="status-badge badge-on-the-way">
                            {{ ucwords(str_replace('_', ' ', $activeRequest->status)) }}
                        </span>
                    @endif
                </div>
                <div class="fixgo-card-body">
                    @if($activeRequest)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon blue" style="width:44px;height:44px;font-size:18px">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1a3c6e">
                                    {{ $activeRequest->serviceCategory->name ?? 'N/A' }}
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    {{ $activeRequest->request_number }}
                                </div>
                            </div>
                        </div>
                        @if($activeRequest->mechanic)
                        <div class="d-flex align-items-center gap-2 mb-3 p-3"
                            style="background:#f0f4ff;border-radius:10px">
                            <div class="nav-user-avatar" style="width:36px;height:36px">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <div style="font-weight:600;font-size:13px">
                                    {{ $activeRequest->mechanic->user->name }}
                                </div>
                                <div style="font-size:12px;color:#f97316">
                                    <i class="fas fa-star"></i>
                                    {{ $activeRequest->mechanic->rating }}
                                </div>
                            </div>
                            <div style="text-align:right">
                                <div style="font-size:11px;color:#6b7280">ETA</div>
                                <div style="font-weight:700;color:#1a3c6e">~8 mins</div>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.track', $activeRequest->id) }}"
                            class="btn btn-fixgo btn-sm flex-1" style="width:auto;flex:1">
                               <span style="color: white" ><i class="fas fa-map-marker-alt me-1"></i> Track</span>
                            </a>
                            <button class="btn btn-outline-danger btn-sm flex-1">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-car-crash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No active requests right now.</p>
                            <a href="{{ route('user.request-assistance') }}"
                            class="btn btn-fixgo" style="width:auto;padding:10px 20px">
                                <i class="fas fa-plus me-2"style="color: white"></i><span style="color: white"> Request Help</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-md-6">
            <div class="fixgo-card h-100">
                <div class="fixgo-card-header">
                    <h6>{{--<i class="fas fa-th me-2 text-primary"></i>--}}Quick Actions</h6>
                </div>
                <div class="fixgo-card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('user.request-assistance') }}"
                            class="quick-action-card text-decoration-none">
                                <div class="qa-icon" style="background:linear-gradient(135deg,#3b82f6,#1a3c6e)">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <span>Request Help</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.mechanics') }}"
                            class="quick-action-card text-decoration-none">
                            {{-- style="background:linear-gradient(135deg,#3b82f6,#1a3c6e)" --}}
                                <div class="qa-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                                    <i class="fas fa-search"></i>
                                </div>
                                <span>Find Mechanic</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.my-requests') }}"
                            class="quick-action-card text-decoration-none">
                            {{-- style="background:linear-gradient(135deg,#3b82f6,#1a3c6e)" --}}
                                <div class="qa-icon" style="background:linear-gradient(135deg,#f97316,#ef4444)">
                                    <i class="fas fa-history"></i>
                                </div>
                                <span>My Requests</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('user.favourites') }}"
                            class="quick-action-card text-decoration-none">
                            {{-- style="background:linear-gradient(135deg,#3b82f6,#1a3c6e)" --}}
                                <div class="qa-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"  >
                                    <i class="fas fa-heart" ></i>
                                </div>
                                <span>Favourites</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Requests --}}
        <div class="col-12">
            <div class="fixgo-card">
                <div class="fixgo-card-header">
                    <h6><i class="fas fa-history me-2 text-primary"></i>Recent Requests</h6>
                    <a href="{{ route('user.my-requests') }}"
                    class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="fixgo-card-body p-0">
                    @if($recentRequests->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8fafc">
                                <tr>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600;padding:12px 20px">
                                        REQUEST
                                    </th>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600">MECHANIC</th>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600">DATE</th>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600">AMOUNT</th>
                                    <th style="font-size:12px;color:#6b7280;font-weight:600">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $req)
                                <tr>
                                    <td style="padding:14px 20px">
                                        <div style="font-weight:600;font-size:14px">
                                            {{ $req->serviceCategory->name ?? 'N/A' }}
                                        </div>
                                        <div style="font-size:12px;color:#6b7280">
                                            {{ $req->request_number }}
                                        </div>
                                    </td>
                                    <td style="font-size:13px">
                                        {{ $req->mechanic->user->name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="status-badge badge-{{ str_replace('_','-',$req->status) }}">
                                            {{ ucwords(str_replace('_', ' ', $req->status)) }}
                                        </span>
                                    </td>
                                    <td style="font-size:13px;color:#6b7280">
                                        {{ $req->created_at->format('M d, Y') }}
                                    </td>
                                    <td style="font-weight:600;color:#1a3c6e">
                                        {{ $req->price ? 'KSh '.number_format($req->price) : '—' }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        {{-- <i class="fas fa-clipboard fa-3x text-muted mb-3"></i> --}}
                        <p class="text-muted" >No requests yet.</p>
                        <a href="{{ route('user.request-assistance') }}"
                        class="btn btn-fixgo" style="width:auto;padding:10px 20px">
                            <span style="color: white" >Make your first request</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
    