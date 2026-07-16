@extends('layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        {{--<i class="fas fa-chart-bar me-2 text-primary"></i> Reports & Analytics--}}
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        Platform performance and revenue insights.
    </p>
</div>

{{-- Key Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalRevenue) }}</h3>
                <p>Total Revenue (KSh)</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($thisMonthRevenue) }}</h3>
                <p>This Month (KSh)</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-check-double"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalCompleted) }}</h3>
                <p>Completed Jobs</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($avgRating ?? 0, 1) }}</h3>
                <p>Avg Rating</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- Monthly Revenue Chart --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Monthly Revenue & Requests (Last 6 Months)
                </h6>
            </div>
            <div class="fixgo-card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Mechanics --}}
    <div class="col-lg-4">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-header">
                <h6>{{--<i class="fas fa-trophy me-2 text-warning"></i>--}}Top Mechanics</h6>
            </div>
            <div class="fixgo-card-body p-0">
                @foreach($topMechanics as $index => $mech)
                <div class="d-flex align-items-center gap-3 p-3"
                     style="border-bottom:1px solid #f0f4ff">
                    <div style="width:28px;height:28px;border-radius:50%;
                                background:{{ $index === 0 ? '#f59e0b' : ($index === 1 ? '#9ca3af' : ($index === 2 ? '#b45309' : '#f0f4ff')) }};
                                color:{{ $index < 3 ? 'white' : '#6b7280' }};
                                display:flex;align-items:center;justify-content:center;
                                font-size:12px;font-weight:700;flex-shrink:0">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p style="font-weight:600;color:#1a3c6e;margin:0;font-size:13px">
                            {{ $mech->user->name ?? 'N/A' }}
                        </p>
                        <p style="font-size:11px;color:#6b7280;margin:0">
                            {{ $mech->total_jobs }} jobs
                        </p>
                    </div>
                    <span style="color:#f59e0b;font-size:12px;font-weight:700">
                        <i class="fas fa-star"></i>
                        {{ number_format($mech->rating, 1) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Top Services & Comparison --}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-chart-bar me-2 text-primary"></i>Top Services</h6>
            </div>
            <div class="fixgo-card-body">
                @php $maxSvc = $topServices->max('total') ?: 1; @endphp
                @foreach($topServices as $svc)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:13px;font-weight:600;color:#374151">
                            {{ $svc->serviceCategory->name ?? 'N/A' }}
                        </span>
                        <span style="font-size:13px;font-weight:700;color:#1a3c6e">
                            {{ $svc->total }}
                        </span>
                    </div>
                    <div style="background:#f0f4ff;border-radius:10px;
                                height:12px;overflow:hidden">
                        <div style="width:{{ ($svc->total / $maxSvc) * 100 }}%;
                                    height:100%;
                                    background:linear-gradient(90deg,#1a3c6e,#3b82f6);
                                    border-radius:10px">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-exchange-alt me-2 text-primary"></i>
                    Month Comparison
                </h6>
            </div>
            <div class="fixgo-card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div style="background:#eff6ff;border-radius:12px;padding:20px;
                                    text-align:center">
                            <p style="font-size:12px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:8px">
                                This Month
                            </p>
                            <h3 style="font-size:24px;font-weight:800;
                                        color:#1a3c6e;margin:0">
                                KSh {{ number_format($thisMonthRevenue) }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:12px;padding:20px;
                                    text-align:center">
                            <p style="font-size:12px;color:#6b7280;font-weight:600;
                                       text-transform:uppercase;margin-bottom:8px">
                                Last Month
                            </p>
                            <h3 style="font-size:24px;font-weight:800;
                                        color:#6b7280;margin:0">
                                KSh {{ number_format($lastMonthRevenue) }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-12">
                        @php
                            $change = $lastMonthRevenue > 0
                                ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                                : 0;
                        @endphp
                        <div style="background:{{ $change >= 0 ? '#d1fae5' : '#fee2e2' }};
                                    border-radius:12px;padding:16px;text-align:center">
                            <span style="font-size:20px;font-weight:800;
                                          color:{{ $change >= 0 ? '#065f46' : '#991b1b' }}">
                                <i class="fas fa-arrow-{{ $change >= 0 ? 'up' : 'down' }} me-2"></i>
                                {{ abs(round($change, 1)) }}%
                            </span>
                            <p style="font-size:13px;color:#6b7280;margin:4px 0 0">
                                {{ $change >= 0 ? 'increase' : 'decrease' }} from last month
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthlyData = @json($monthlyRevenue);
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.month),
        datasets: [
            {
                label: 'Revenue (KSh)',
                data: monthlyData.map(d => d.revenue),
                backgroundColor: 'rgba(59,130,246,0.8)',
                borderRadius: 8,
                yAxisID: 'y',
            },
            {
                label: 'Requests',
                data: monthlyData.map(d => d.requests),
                type: 'line',
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.1)',
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 5,
                fill: true,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                grid: { color: '#f0f4ff' },
                ticks: {
                    callback: val => 'KSh ' + val.toLocaleString()
                }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush