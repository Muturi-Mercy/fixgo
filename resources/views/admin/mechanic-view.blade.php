@extends('layouts.app')

@section('title', 'Mechanic Details')
@section('page-title', 'Mechanic Details')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.mechanics') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Mechanics
    </a>
    <div class="d-flex gap-2">

        {{-- Pending: Approve & Reject --}}
        @if($mechanic->verification_status === 'pending')
        <form method="POST"
            action="{{ route('admin.mechanics.approve', $mechanic->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success"
                    style="padding:9px 20px">
                <i class="fas fa-check-circle me-2"></i> Approve
            </button>
        </form>
        <form method="POST"
            action="{{ route('admin.mechanics.reject', $mechanic->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline-danger"
                    style="padding:9px 20px"
                    onclick="return confirm('Reject this mechanic?')">
                <i class="fas fa-times-circle me-2"></i> Reject
            </button>
        </form>
        @endif

        {{-- Approved: Show Revoke button --}}
        @if($mechanic->verification_status === 'approved')
        <form method="POST"
            action="{{ route('admin.mechanics.reject', $mechanic->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline-danger"
                    style="padding:9px 20px"
                    onclick="return confirm('Revoke this mechanic\'s verification?')">
                <i class="fas fa-times-circle me-2"></i> Revoke Verification
            </button>
        </form>
        @endif

        {{-- Rejected: Show Undo button --}}
        @if($mechanic->verification_status === 'rejected')
        <div style="background:#fef3c7;color:#92400e;padding:9px 16px;
                    border-radius:10px;font-size:13px;font-weight:600">
            <i class="fas fa-times-circle me-2 text-danger"></i>
            Verification Rejected
        </div>
        <form method="POST"
            action="{{ route('admin.mechanics.undo-rejection', $mechanic->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-warning"
                    style="padding:9px 20px;color:white"
                    onclick="return confirm('Reset verification to pending so you can review again?')">
                <i class="fas fa-undo me-2"></i> Undo Rejection
            </button>
        </form>
        @endif

        {{-- Suspend / Activate --}}
        <form method="POST"
            action="{{ route('admin.mechanics.toggle-status', $mechanic->id) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="btn {{ $mechanic->user->status === 'active' ? 'btn-warning' : 'btn-success' }}"
                    style="padding:9px 20px;color:white"
                    onclick="return confirm('{{ $mechanic->user->status === 'active' ? 'Suspend this mechanic account?' : 'Activate this mechanic account?' }}')">
                <i class="fas fa-{{ $mechanic->user->status === 'active' ? 'ban' : 'check-circle' }} me-2"></i>
                {{ $mechanic->user->status === 'active' ? 'Suspend Account' : 'Activate Account' }}
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="fixgo-card mb-4">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:30px 20px 50px;border-radius:14px 14px 0 0;text-align:center">
            </div>
            <div style="margin-top:-40px;padding:0 20px 24px;text-align:center">
                <div style="width:80px;height:80px;border-radius:50%;
                            border:4px solid white;
                            box-shadow:0 4px 15px rgba(0,0,0,0.15);
                            overflow:hidden;margin:0 auto 12px;
                            background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                            display:flex;align-items:center;justify-content:center">
                    @if($mechanic->user->profile_photo)
                        <img src="{{ asset('storage/'.$mechanic->user->profile_photo) }}"
                             style="width:100%;height:100%;object-fit:cover">
                    @else
                        <i class="fas fa-user" style="font-size:32px;color:white"></i>
                    @endif
                </div>

                <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:4px">
                    {{ $mechanic->user->name }}
                </h5>
                <p class="text-muted mb-2" style="font-size:13px">
                    {{ $mechanic->user->email }}
                </p>

                {{-- Verification Badge --}}                {{-- Verification Status --}}
                <div class="mb-2">
                    @if($mechanic->verification_status === 'approved')
                        <span style="background:#10b981;color:white;padding:5px 14px;
                                    border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-check-circle me-1"></i> Verified
                        </span>
                    @elseif($mechanic->verification_status === 'pending')
                        <span style="background:#f59e0b;color:white;padding:5px 14px;
                                    border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-clock me-1"></i> Pending Verification
                        </span>
                    @else
                        <span style="background:#ef4444;color:white;padding:5px 14px;
                                    border-radius:20px;font-size:12px;font-weight:600">
                            <i class="fas fa-times-circle me-1"></i> Verification Rejected
                        </span>
                    @endif
                </div>

                {{-- Account Status --}}
                <div>
                    @if($mechanic->user->status === 'active')
                        <span style="background:#d1fae5;color:#065f46;padding:5px 14px;
                                    border-radius:20px;font-size:12px;font-weight:600;
                                    border:1px solid #10b981">
                            <i class="fas fa-circle me-1" style="font-size:8px"></i> Account Active
                        </span>
                    @else
                        <span style="background:#fee2e2;color:#991b1b;padding:5px 14px;
                                    border-radius:20px;font-size:12px;font-weight:600;
                                    border:1px solid #ef4444">
                            <i class="fas fa-ban me-1"></i> Account Suspended
                        </span>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="mechanic-stats mt-4">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ number_format($mechanic->rating, 1) }}
                        </span>
                        <span class="mechanic-stat-label">Rating</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">{{ $mechanic->total_jobs }}</span>
                        <span class="mechanic-stat-label">Jobs</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $mechanic->years_of_experience ?? 0 }}
                        </span>
                        <span class="mechanic-stat-label">Yrs Exp</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Details</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label">Phone</span>
                    <span class="confirm-value">{{ $mechanic->user->phone ?? '—' }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Location</span>
                    <span class="confirm-value">
                        {{ $mechanic->location_address ?? '—' }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Specialization</span>
                    <span class="confirm-value">{{ $mechanic->specialization ?? '—' }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Price Range</span>
                    <span class="confirm-value">
                        @if($mechanic->min_price && $mechanic->max_price)
                            KSh {{ number_format($mechanic->min_price) }} –
                            KSh {{ number_format($mechanic->max_price) }}
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Availability</span>
                    <span class="confirm-value">
                        <span class="status-badge
                              {{ $mechanic->availability === 'available' ? 'badge-available' : 'badge-offline' }}">
                            {{ ucfirst($mechanic->availability) }}
                        </span>
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Joined</span>
                    <span class="confirm-value">
                        {{ $mechanic->created_at->format('M d, Y') }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Account Status</span>
                    <span class="confirm-value">
                        @if($mechanic->user->status === 'active')
                            <span class="status-badge badge-available">Active</span>
                        @else
                            <span class="status-badge badge-cancelled">Suspended</span>
                        @endif
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">Verification</span>
                    <span class="confirm-value">
                        @if($mechanic->verification_status === 'approved')
                            <span class="status-badge badge-completed">Verified</span>
                        @elseif($mechanic->verification_status === 'pending')
                            <span class="status-badge badge-pending">Pending</span>
                        @else
                            <span class="status-badge badge-cancelled">Rejected</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-8">

        {{-- Tabs --}}
        <div class="fixgo-card mb-4">
            <div class="fixgo-card-body" style="padding:8px 16px">
                <ul class="nav nav-pills gap-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#jobs" data-bs-toggle="tab">
                            <i class="fas fa-briefcase me-1"></i> Jobs
                            ({{ $mechanic->breakdownRequests->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#portfolio" data-bs-toggle="tab">
                            <i class="fas fa-images me-1"></i> Portfolio
                            ({{ $mechanic->portfolios->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reviews_tab" data-bs-toggle="tab">
                            <i class="fas fa-star me-1"></i> Reviews
                            ({{ $mechanic->ratings->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#documents" data-bs-toggle="tab">
                            <i class="fas fa-file me-1"></i> Documents
                            ({{ $mechanic->documents->count() }})
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">

            {{-- Jobs Tab --}}
            <div class="tab-pane fade show active" id="jobs">
                <div class="fixgo-card">
                    <div class="fixgo-card-body p-0">
                        @if($mechanic->breakdownRequests->count())
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8fafc">
                                <tr>
                                    <th style="font-size:12px;color:#6b7280;
                                               font-weight:600;padding:10px 20px">SERVICE</th>
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
                                @foreach($mechanic->breakdownRequests->take(10) as $job)
                                <tr>
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600">
                                        {{ $job->serviceCategory->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="status-badge
                                              badge-{{ str_replace('_','-',$job->status) }}">
                                            {{ ucwords(str_replace('_',' ',$job->status)) }}
                                        </span>
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">
                                        {{ $job->created_at->format('M d, Y') }}
                                    </td>
                                    <td style="font-weight:600;color:#1a3c6e;font-size:13px">
                                        {{ $job->price ? 'KSh '.number_format($job->price) : '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-4">
                            <p class="text-muted">No jobs yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Portfolio Tab --}}
            <div class="tab-pane fade" id="portfolio">
                @if($mechanic->portfolios->count())
                <div class="row g-3">
                    @foreach($mechanic->portfolios as $portfolio)
                    <div class="col-md-6">
                        <div class="fixgo-card">
                            <div class="fixgo-card-header">
                                <h6 style="font-size:14px">{{ $portfolio->title }}</h6>
                                <span class="status-badge badge-accepted">
                                    {{ $portfolio->category }}
                                </span>
                            </div>
                            <div class="fixgo-card-body">
                                <div class="row g-2">
                                    @foreach($portfolio->images->take(4) as $img)
                                    <div class="col-6">
                                        <img src="{{ asset('storage/'.$img->image_path) }}"
                                             class="w-100 rounded"
                                             style="height:80px;object-fit:cover">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="fixgo-card">
                    <div class="fixgo-card-body text-center py-4">
                        <p class="text-muted">No portfolio items.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Reviews Tab --}}
            <div class="tab-pane fade" id="reviews_tab">
                @if($mechanic->ratings->count())
                    @foreach($mechanic->ratings as $review)
                    <div class="fixgo-card mb-3">
                        <div class="fixgo-card-body">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-weight:600;font-size:14px;color:#1a3c6e">
                                    {{ $review->user->name ?? 'User' }}
                                </span>
                                <span style="font-size:12px;color:#9ca3af">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <div style="color:#f59e0b;margin-bottom:6px">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star
                                       {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            @if($review->review)
                            <p style="font-size:14px;color:#374151;margin:0">
                                {{ $review->review }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="fixgo-card">
                    <div class="fixgo-card-body text-center py-4">
                        <p class="text-muted">No reviews yet.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Documents Tab --}}
            <div class="tab-pane fade" id="documents">
                @if($mechanic->documents->count())
                <div class="row g-3">
                    @foreach($mechanic->documents as $doc)
                    <div class="col-md-6">
                        <div class="fixgo-card">
                            <div class="fixgo-card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stat-icon blue"
                                         style="width:44px;height:44px;font-size:18px;flex-shrink:0">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p style="font-weight:600;color:#1a3c6e;
                                                   margin:0;font-size:14px">
                                            {{ $doc->document_type }}
                                        </p>
                                        <span class="status-badge
                                              {{ $doc->status === 'approved' ? 'badge-completed' :
                                                 ($doc->status === 'pending' ? 'badge-pending' : 'badge-cancelled') }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </div>
                                    <a href="{{ asset('storage/'.$doc->document_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary"
                                       style="padding:5px 12px">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="fixgo-card">
                    <div class="fixgo-card-body text-center py-4">
                        <i class="fas fa-file fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No documents uploaded.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.confirm-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f4ff;
    font-size: 13px;
}
.confirm-row:last-child { border-bottom: none; }
.confirm-label { color: #6b7280; }
.confirm-value { font-weight: 600; color: #1a3c6e; text-align: right; }
</style>
@endpush