@extends('layouts.app')

@section('title', 'Manage Mechanics')
@section('page-title', 'Manage Mechanics')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            {{--<i class="fas fa-tools me-2 text-primary"></i> Manage Mechanics--}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
           Manage mechanic accounts.
        </p>
    </div>
    @if($pendingCount)
    <div style="background:#fef3c7;color:#d97706;padding:8px 16px;
                border-radius:10px;font-size:13px;font-weight:600">
        <i class="fas fa-clock me-1"></i>
        {{ $pendingCount }} pending approval
    </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Filter Tabs --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body" style="padding:12px 20px">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <span style="padding-right: 80px">
            <a href="{{ route('admin.mechanics') }}"
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                All
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('admin.mechanics', ['status'=>'pending']) }}"
               class="filter-tab {{ request('status')=='pending' ? 'active' : '' }}">
                <i class="fas fa-clock me-1"></i> Pending
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('admin.mechanics', ['status'=>'approved']) }}"
               class="filter-tab {{ request('status')=='approved' ? 'active' : '' }}">
                <i class="fas fa-check me-1"></i> Approved
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('admin.mechanics', ['status'=>'rejected']) }}"
               class="filter-tab {{ request('status')=='rejected' ? 'active' : '' }}">
                <i class="fas fa-times me-1"></i> Rejected
            </a>
            </span>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.mechanics') }}"
                  class="ms-auto d-flex gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="text" name="search" class="form-control"
                       style="width:220px;font-size:13px"
                       placeholder="Search mechanic..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary"
                        style="padding:8px 16px">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Mechanics Table --}}
<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($mechanics->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:12px;color:#6b7280;font-weight:600;padding:12px 20px">MECHANIC</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">PHONE</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">SPECIALIZATION</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">RATING</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">JOBS</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">VERIFICATION</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">ACCOUNT</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mechanics as $mech)
                    <tr>
                        <td style="padding:14px 20px">
                            <div class="d-flex align-items-center gap-2">
                                <div class="nav-user-avatar"
                                     style="width:38px;height:38px;font-size:15px;flex-shrink:0">
                                    @if($mech->user->profile_photo)
                                        <img src="{{ asset('storage/'.$mech->user->profile_photo) }}"
                                             style="width:100%;height:100%;
                                                    object-fit:cover;border-radius:50%">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;color:#1a3c6e">
                                        {{ $mech->user->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:11px;color:#6b7280">
                                        {{ $mech->user->email ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px">{{ $mech->user->phone ?? '—' }}</td>
                        <td style="font-size:13px">
                            {{ \Illuminate\Support\Str::limit($mech->specialization ?? '—', 25) }}
                        </td>
                        <td>
                            <span style="color:#f59e0b;font-weight:700;font-size:13px">
                                <i class="fas fa-star"></i>
                                {{ number_format($mech->rating, 1) }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:#1a3c6e;font-size:13px">
                            {{ $mech->total_jobs }}
                        </td>
                        {{-- Verification Status --}}
                        <td>
                            @if($mech->verification_status === 'pending')
                                <span class="status-badge badge-pending">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @elseif($mech->verification_status === 'approved')
                                <span class="status-badge badge-completed">
                                    <i class="fas fa-check-circle me-1"></i>Verified
                                </span>
                            @else
                                <span class="status-badge badge-cancelled">
                                    <i class="fas fa-times-circle me-1"></i>Rejected
                                </span>
                            @endif
                        </td>

                        {{-- Account Status --}}
                        <td>
                            @if($mech->user->status === 'active')
                                <span class="status-badge badge-available">
                                    <i class="fas fa-circle me-1" style="font-size:8px"></i>Active
                                </span>
                            @else
                                <span class="status-badge badge-cancelled">
                                    <i class="fas fa-ban me-1"></i>Suspended
                                </span>
                            @endif
                        </td>
                       <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.mechanics.view', $mech->id) }}"
                                class="btn btn-sm btn-outline-primary"
                                style="padding:5px 10px;font-size:11px">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Pending: Show Approve & Reject --}}
                                @if($mech->verification_status === 'pending')
                                <form method="POST"
                                    action="{{ route('admin.mechanics.approve', $mech->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-success"
                                            style="padding:5px 10px;font-size:11px"
                                            title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form method="POST"
                                    action="{{ route('admin.mechanics.reject', $mech->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            style="padding:5px 10px;font-size:11px"
                                            title="Reject"
                                            onclick="return confirm('Reject this mechanic?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Approved: Show Reject button --}}
                                @if($mech->verification_status === 'approved')
                                <form method="POST"
                                    action="{{ route('admin.mechanics.reject', $mech->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            style="padding:5px 10px;font-size:11px"
                                            title="Revoke Verification"
                                            onclick="return confirm('Revoke this mechanic\'s verification?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Rejected: Show Undo button --}}
                                @if($mech->verification_status === 'rejected')
                                <form method="POST"
                                    action="{{ route('admin.mechanics.undo-rejection', $mech->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-warning"
                                            style="padding:5px 10px;font-size:11px"
                                            title="Undo Rejection — Reset to Pending"
                                            onclick="return confirm('Reset verification to pending?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Suspend / Activate --}}
                                <form method="POST"
                                    action="{{ route('admin.mechanics.toggle-status', $mech->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $mech->user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            style="padding:5px 10px;font-size:11px"
                                            title="{{ $mech->user->status === 'active' ? 'Suspend Account' : 'Activate Account' }}"
                                            onclick="return confirm('{{ $mech->user->status === 'active' ? 'Suspend this mechanic?' : 'Activate this mechanic?' }}')">
                                        <i class="fas fa-{{ $mech->user->status === 'active' ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $mechanics->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-tools fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Mechanics Found</h5>
        </div>
        @endif
    </div>
</div>

@endsection