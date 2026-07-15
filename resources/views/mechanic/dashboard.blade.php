@extends('layouts.app')

@section('title', 'Mechanic Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link">
        <i class="fas fa-bell"></i> Service Requests
        <span class="nav-badge nav-badge-orange">{{ $newRequests->count() }}</span>
    </a>
    <a href="{{ route('mechanic.my-jobs') }}" class="nav-link">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="{{ route('mechanic.earnings') }}" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link">
        <i class="fas fa-star"></i> Reviews
    </a>
    <a href="{{ route('mechanic.profile') }}" class="nav-link">
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

{{-- Welcome + Availability --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
       {{-- <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            Welcome, {{ auth()->user()->name }}! 
        </h4> --}}
        <p class="text-muted mb-0" style="font-size:14px">
            @if($mechanic->verification_status === 'approved')
                <i class="fas fa-check-circle text-success me-1"></i> Verified Mechanic
            @elseif($mechanic->verification_status === 'pending')
                <i class="fas fa-clock text-warning me-1"></i> Verification Pending
            @else
                <i class="fas fa-times-circle text-danger me-1"></i> Not Verified
            @endif
        </p>
    </div>

    {{-- Availability Toggle --}}
    <div class="d-flex align-items-center gap-3">
        <span style="font-size:14px;font-weight:600;color:#374151">Availability:</span>
        <div class="availability-selector">
            <button class="avail-btn {{ $mechanic->availability === 'available' ? 'active-available' : '' }}"
                    onclick="setAvailability('available')">
                <i class="fas fa-circle me-1" style="font-size:8px"></i> Available
            </button>
            <button class="avail-btn {{ $mechanic->availability === 'busy' ? 'active-busy' : '' }}"
                    onclick="setAvailability('busy')">
                <i class="fas fa-circle me-1" style="font-size:8px"></i> Busy
            </button>
            <button class="avail-btn {{ $mechanic->availability === 'offline' ? 'active-offline' : '' }}"
                    onclick="setAvailability('offline')">
                <i class="fas fa-circle me-1" style="font-size:8px"></i> Offline
            </button>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <h3>{{ $todayJobs }}</h3>
                <p>Today's Jobs</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
            <div class="stat-info">
                <h3>{{ $completedJobs }}</h3>
                <p>Completed Jobs</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($todayEarnings) }}</h3>
                <p>Today's Earnings</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-wallet"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalEarnings) }}</h3>
                <p>Total Earnings</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- New Service Requests --}}
    <div class="col-lg-6">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-header">
                <h6>{{-- <i class="fas fa-bell me-2 text-warning"></i> --}}New Requests</h6>
                <a href="{{ route('mechanic.service-requests') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                @if($newRequests->count())
                    @foreach($newRequests as $req)
                    <div class="request-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="stat-icon blue"
                                 style="width:42px;height:42px;font-size:16px;flex-shrink:0">
                                <i class="{{ getServiceIcon($req->serviceCategory->name ?? '') }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-weight:700;font-size:14px;color:#1a3c6e">
                                        {{ $req->serviceCategory->name ?? 'N/A' }}
                                    </span>
                                    <span class="status-badge badge-pending">New</span>
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $req->user->name }}
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                    {{ \Illuminate\Support\Str::limit($req->user_address ?? 'Location shared', 40) }}
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $req->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <form method="POST"
                                  action="{{ route('mechanic.accept-request', $req->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm btn-success px-3">
                                    <i class="fas fa-check me-1"></i> Accept
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('mechanic.decline-request', $req->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger px-3">
                                    <i class="fas fa-times me-1"></i> Decline
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No new requests right now.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Active Jobs --}}
    <div class="col-lg-6">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-header">
            <h6>{{--<i class="fas fa-bolt me-2 text-warning"></i>--}}Active Jobs</h6>
                <a href="{{ route('mechanic.my-jobs') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                @if($activeJobs->count())
                    @foreach($activeJobs as $job)
                    <div class="request-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="stat-icon green"
                                 style="width:42px;height:42px;font-size:16px;flex-shrink:0">
                                <i class="{{ getServiceIcon($job->serviceCategory->name ?? '') }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span style="font-weight:700;font-size:14px;color:#1a3c6e; padding-right:8px">
                                        {{ $job->serviceCategory->name ?? 'N/A' }}
                                    </span>
                                    <span class="status-badge badge-{{ str_replace('_','-',$job->status) }}">
                                        {{ ucwords(str_replace('_',' ',$job->status)) }}
                                    </span>
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $job->user->name }}
                                </div>
                                <div style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-hashtag me-1"></i>
                                    {{ $job->request_number }}
                                </div>
                            </div>
                        </div>
                        {{-- Status Update --}}
                        <form method="POST"
                              action="{{ route('mechanic.update-request-status', $job->id) }}"
                              class="mt-2">
                            @csrf @method('PATCH')
                            <div class="d-flex gap-2">
                                <select name="status" class="form-select form-control"
                                        style="font-size:13px;padding:6px 10px">
                                    <option value="accepted"
                                        {{ $job->status === 'accepted' ? 'selected':'' }}>
                                        Accepted
                                    </option>
                                    <option value="on_the_way"
                                        {{ $job->status === 'on_the_way' ? 'selected':'' }}>
                                        On The Way
                                    </option>
                                    <option value="arrived"
                                        {{ $job->status === 'arrived' ? 'selected':'' }}>
                                        Arrived
                                    </option>
                                    <option value="repairing"
                                        {{ $job->status === 'repairing' ? 'selected':'' }}>
                                        Repairing
                                    </option>
                                    <option value="completed"
                                        {{ $job->status === 'completed' ? 'selected':'' }}>
                                        Completed
                                    </option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-fixgo"
                                        style="width:auto;padding:6px 14px;white-space:nowrap">
                                    <span style="color: white"><i class="fas fa-save"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No active jobs right now.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Jobs Table --}}
    <div class="col-12">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-history me-2 text-primary"></i>Recent Jobs</h6>
                <a href="{{ route('mechanic.my-jobs') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                @if($recentJobs->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="font-size:12px;color:#6b7280;
                                           font-weight:600;padding:12px 20px">
                                    REQUEST
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    CUSTOMER
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    STATUS
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    DATE
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    AMOUNT
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentJobs as $job)
                            <tr>
                                <td style="padding:14px 20px">
                                    <div style="font-weight:600;font-size:14px">
                                        {{ $job->serviceCategory->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280">
                                        {{ $job->request_number }}
                                    </div>
                                </td>
                                <td style="font-size:13px">{{ $job->user->name }}</td>
                                <td>
                                    <span class="status-badge
                                          badge-{{ str_replace('_','-',$job->status) }}">
                                        {{ ucwords(str_replace('_',' ',$job->status)) }}
                                    </span>
                                </td>
                                <td style="font-size:13px;color:#6b7280">
                                    {{ $job->created_at->format('M d, Y') }}
                                </td>
                                <td style="font-weight:600;color:#1a3c6e">
                                    {{ $job->price ? 'KSh '.number_format($job->price) : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No jobs yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.availability-selector {
    display: flex;
    gap: 6px;
    background: #f0f4ff;
    padding: 4px;
    border-radius: 10px;
}
.avail-btn {
    padding: 6px 14px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
    color: #6b7280;
    transition: all 0.2s ease;
}
.avail-btn:hover { background: white; }
.active-available { background: #10b981 !important; color: white !important; }
.active-busy { background: #ef4444 !important; color: white !important; }
.active-offline { background: #6b7280 !important; color: white !important; }
.request-item {
    padding: 16px 20px;
    border-bottom: 1px solid #f0f4ff;
}
.request-item:last-child { border-bottom: none; }
</style>
@endpush

@push('scripts')
<script>
function setAvailability(status) {
    fetch('{{ route("mechanic.update-availability") }}', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ availability: status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.avail-btn').forEach(btn => {
                btn.classList.remove('active-available','active-busy','active-offline');
            });
            event.target.classList.add('active-' + status);
        }
    });
}
</script>
@endpush