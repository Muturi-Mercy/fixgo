@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

{{-- Welcome --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            {{--Welcome, {{ auth()->user()->name }}! --}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
            Here's what's happening on the platform today.
        </p>
    </div>
    <div style="font-size:13px;color:#6b7280">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('l, M d Y') }}
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalUsers) }}</h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-tools"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalMechanics) }}</h3>
                <p>Total Mechanics</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalRequests) }}</h3>
                <p>Total Requests</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($completedToday) }}</h3>
                <p>Completed Today</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalEarnings) }}</h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- Weekly Requests Chart --}}
    <div class="col-lg-8">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-chart-line me-2 text-primary"></i>Requests Overview (Last 7 Days)</h6>
            </div>
            <div class="fixgo-card-body">
                <canvas id="weeklyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Requests by Status Donut --}}
    <div class="col-lg-4">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-chart-pie me-2 text-primary"></i>Requests by Status</h6>
            </div>
            <div class="fixgo-card-body">
                <canvas id="statusChart" height="180"></canvas>
                <div class="mt-3">
                    @foreach($requestsByStatus as $status => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:13px;color:#374151;text-transform:capitalize">
                            {{ ucwords(str_replace('_',' ',$status)) }}
                        </span>
                        <span style="font-size:13px;font-weight:700;color:#1a3c6e">
                            {{ $count }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- Pending Mechanic Registrations --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-user-clock me-2 text-warning"></i>
                    Mechanic Registrations
                </h6>
                <a href="{{ route('admin.mechanics') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                @if($recentMechanics->count())
                <table class="table table-hover mb-0">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th style="font-size:12px;color:#6b7280;
                                       font-weight:600;padding:10px 20px">MECHANIC</th>
                            <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                            <th style="font-size:12px;color:#6b7280;font-weight:600">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentMechanics as $mech)
                        <tr>
                            <td style="padding:12px 20px">
                                <div style="font-weight:600;font-size:13px">
                                    {{ $mech->user->name ?? 'N/A' }}
                                </div>
                                <div style="font-size:11px;color:#6b7280">
                                    {{ $mech->user->email ?? '' }}
                                </div>
                            </td>
                            <td>
                                @if($mech->verification_status === 'pending')
                                    <span class="status-badge badge-pending">Pending</span>
                                @elseif($mech->verification_status === 'approved')
                                    <span class="status-badge badge-completed">Approved</span>
                                @else
                                    <span class="status-badge badge-cancelled">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($mech->verification_status === 'pending')
                                    <form method="POST"
                                          action="{{ route('admin.mechanics.approve', $mech->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                style="padding:4px 10px;font-size:11px">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST"
                                          action="{{ route('admin.mechanics.reject', $mech->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                style="padding:4px 10px;font-size:11px">
                                            Reject
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('admin.mechanics.view', $mech->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       style="padding:4px 10px;font-size:11px">View</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No mechanic registrations yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Services --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-chart-bar me-2 text-primary"></i>Top Services</h6>
            </div>
            <div class="fixgo-card-body">
                @php $maxService = $topServices->max('total') ?: 1; @endphp
                @foreach($topServices as $service)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:13px;font-weight:600;color:#374151">
                            {{ $service->serviceCategory->name ?? 'N/A' }}
                        </span>
                        <span style="font-size:13px;font-weight:700;color:#1a3c6e">
                            {{ $service->total }}
                        </span>
                    </div>
                    <div style="background:#f0f4ff;border-radius:10px;
                                height:10px;overflow:hidden">
                        <div style="width:{{ ($service->total / $maxService) * 100 }}%;
                                    height:100%;
                                    background:linear-gradient(90deg,#1a3c6e,#3b82f6);
                                    border-radius:10px;
                                    transition:width 0.5s ease">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Recent Requests --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-history me-2 text-primary"></i>Recent Requests</h6>
                <a href="{{ route('admin.requests') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="font-size:12px;color:#6b7280;
                                           font-weight:600;padding:10px 20px">REQUEST</th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">USER</th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">DATE</th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRequests as $req)
                            <tr style="cursor:pointer"
                                onclick="window.location='{{ route('admin.requests.view', $req->id) }}'">
                                <td style="padding:12px 20px">
                                    <div style="font-weight:600;font-size:13px">
                                        {{ $req->serviceCategory->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:11px;color:#6b7280">
                                        {{ $req->request_number }}
                                    </div>
                                </td>
                                <td style="font-size:13px">{{ $req->user->name }}</td>
                                <td>
                                    <span class="status-badge
                                          badge-{{ str_replace('_','-',$req->status) }}">
                                        {{ ucwords(str_replace('_',' ',$req->status)) }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:#6b7280">
                                    {{ $req->created_at->format('M d, Y') }}
                                </td>
                                <td style="font-weight:600;color:#1a3c6e;font-size:13px">
                                    {{ $req->price ? 'KSh '.number_format($req->price) : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Reviews --}}
    <div class="col-lg-4">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>{{--<i class="fas fa-star me-2 text-warning"></i>--}}Recent Reviews</h6>
                <a href="{{ route('admin.reviews') }}"
                   class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="fixgo-card-body p-0">
                @foreach($recentReviews as $review)
                <div class="notification-item">
                    <div class="d-flex gap-2">
                        <div class="nav-user-avatar"
                             style="width:36px;height:36px;font-size:14px;flex-shrink:0">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p style="font-weight:600;font-size:13px;color:#1a3c6e;margin:0">
                                {{ $review->user->name ?? 'N/A' }}
                            </p>
                            <div style="color:#f59e0b;font-size:12px;margin:2px 0">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star
                                       {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            @if($review->review)
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                {{ \Illuminate\Support\Str::limit($review->review, 50) }}
                            </p>
                            @endif
                            <p style="font-size:11px;color:#9ca3af;margin:2px 0">
                                To: {{ $review->mechanic->user->name ?? 'N/A' }}
                                · {{ $review->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
                @if(!$recentReviews->count())
                <div class="text-center py-4">
                    <p class="text-muted" style="font-size:13px">No reviews yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.col-md-2-4 {
    flex: 0 0 auto;
    width: 20%;
}
@media (max-width: 768px) {
    .col-md-2-4 { width: 50%; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Requests Line Chart
const weeklyData = @json($weeklyRequests);
const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
new Chart(weeklyCtx, {
    type: 'line',
    data: {
        labels: weeklyData.map(d => d.date),
        datasets: [
            {
                label: 'Total Requests',
                data: weeklyData.map(d => d.total),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 5,
            },
            {
                label: 'Completed',
                data: weeklyData.map(d => d.completed),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointRadius: 5,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: '#f0f4ff' }
            },
            x: { grid: { display: false } }
        }
    }
});

// Status Donut Chart
const statusData = @json($requestsByStatus);
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s =>
            s.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase())
        ),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: ['#f59e0b','#3b82f6','#06b6d4','#10b981','#ef4444'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush