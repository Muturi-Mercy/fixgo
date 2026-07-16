@extends('layouts.app')

@section('title', 'Earnings')
@section('page-title', 'Earnings')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link">
        <i class="fas fa-bell"></i> Service Requests
    </a>
    <a href="{{ route('mechanic.my-jobs') }}" class="nav-link">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="{{ route('mechanic.earnings') }}" class="nav-link active">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link">
        <i class="fas fa-star"></i> Reviews
    </a>
    <a href="{{ route('mechanic.notifications') }}" class="nav-link">
    <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('mechanic.settings') }}" class="nav-link">
        <i class="fas fa-cog"></i> Settings
    </a>
    <a href="{{ route('mechanic.profile') }}" class="nav-link">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('logout') }}" class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
@endsection

@section('content')

{{-- <div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
       <i class="fas fa-wallet me-2 text-primary"></i> Earnings
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        Track your income and payment history.
    </p>
</div> --}}

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <h3>KSh {{ number_format($thisMonthEarnings) }}</h3>
                <p>This Month</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-wallet"></i></div>
            <div class="stat-info">
                <h3>KSh {{ number_format($totalEarnings) }}</h3>
                <p>Total Earnings</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-receipt"></i></div>
            <div class="stat-info">
                <h3>{{ $recentPayments->count() }}</h3>
                <p>Paid Jobs</p>
            </div>
        </div>
    </div>
</div>

{{-- Recent Payments Table --}}
<div class="fixgo-card">
    <div class="fixgo-card-header">
        <h6><i class="fas fa-history me-2 text-primary"></i>Payment History</h6>
    </div>
    <div class="fixgo-card-body p-0">
        @if($recentPayments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:12px;color:#6b7280;
                                   font-weight:600;padding:12px 20px">JOB</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">
                            CUSTOMER
                        </th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">
                            DATE
                        </th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">
                            AMOUNT
                        </th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">
                            STATUS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayments as $payment)
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600;font-size:14px">
                                {{ $payment->serviceCategory->name ?? 'N/A' }}
                            </div>
                            <div style="font-size:12px;color:#6b7280">
                                {{ $payment->request_number }}
                            </div>
                        </td>
                        <td style="font-size:13px">{{ $payment->user->name }}</td>
                        <td style="font-size:13px;color:#6b7280">
                            {{ $payment->completed_at ?
                               \Carbon\Carbon::parse($payment->completed_at)->format('M d, Y') :
                               $payment->updated_at->format('M d, Y') }}
                        </td>
                        <td style="font-weight:700;color:#10b981;font-size:15px">
                            KSh {{ number_format($payment->price ?? 0) }}
                        </td>
                        <td>
                            <span class="status-badge badge-completed">Paid</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-coins fa-4x text-muted mb-4"></i>
                <h5 style="color:#1a3c6e;font-weight:700">No Earnings Yet</h5>
                <p class="text-muted">Complete jobs to start earning.</p>
            </div>
        @endif
    </div>
</div>

@endsection
