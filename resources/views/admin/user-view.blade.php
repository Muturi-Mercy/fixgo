@extends('layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.users') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Users
    </a>
    <div class="d-flex gap-2">
        <form method="POST"
              action="{{ route('admin.users.toggle-status', $user->id) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="btn {{ $user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                    style="padding:9px 20px">
                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-2"></i>
                {{ $user->status === 'active' ? 'Suspend User' : 'Activate User' }}
            </button>
        </form>
        <form method="POST"
              action="{{ route('admin.users.delete', $user->id) }}"
              onsubmit="return confirm('Permanently delete this user?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"
                    style="padding:9px 20px">
                <i class="fas fa-trash me-2"></i> Delete User
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
                        padding:40px 20px 60px;border-radius:14px 14px 0 0;
                        text-align:center">
            </div>
            <div style="margin-top:-50px;padding:0 20px 24px;text-align:center">

                {{-- Avatar --}}
                <div style="width:90px;height:90px;border-radius:50%;
                            border:4px solid white;
                            box-shadow:0 4px 15px rgba(0,0,0,0.15);
                            overflow:hidden;margin:0 auto 16px;
                            background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                            display:flex;align-items:center;justify-content:center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile_photo) }}"
                             style="width:100%;height:100%;object-fit:cover">
                    @else
                        <i class="fas fa-user" style="font-size:36px;color:white"></i>
                    @endif
                </div>

                <h5 style="font-weight:700;color:#1a3c6e;margin-bottom:4px">
                    {{ $user->name }}
                </h5>
                <p class="text-muted mb-2" style="font-size:13px">
                    {{ $user->email }}
                </p>

                @if($user->status === 'active')
                    <span style="background:#10b981;color:white;padding:5px 14px;
                                 border-radius:20px;font-size:12px;font-weight:600">
                        <i class="fas fa-check-circle me-1"></i> Active
                    </span>
                @else
                    <span style="background:#ef4444;color:white;padding:5px 14px;
                                 border-radius:20px;font-size:12px;font-weight:600">
                        <i class="fas fa-ban me-1"></i> Suspended
                    </span>
                @endif

                {{-- Stats --}}
                <div class="mechanic-stats mt-4">
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $requests->total() }}
                        </span>
                        <span class="mechanic-stat-label">Requests</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ $requests->where('status','completed')->count() }}
                        </span>
                        <span class="mechanic-stat-label">Completed</span>
                    </div>
                    <div class="mechanic-stat-divider"></div>
                    <div class="mechanic-stat">
                        <span class="mechanic-stat-value">
                            {{ \App\Models\Favourite::where('user_id',$user->id)->count() }}
                        </span>
                        <span class="mechanic-stat-label">Favourites</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>User Info</h6>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-phone me-1 text-primary"></i> Phone
                    </span>
                    <span class="confirm-value">{{ $user->phone ?? '—' }}</span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-calendar me-1 text-primary"></i> Joined
                    </span>
                    <span class="confirm-value">
                        {{ $user->created_at->format('M d, Y') }}
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-envelope me-1 text-primary"></i> Verified
                    </span>
                    <span class="confirm-value">
                        @if($user->email_verified_at)
                            <span style="color:#10b981;font-weight:600">Yes</span>
                        @else
                            <span style="color:#ef4444;font-weight:600">No</span>
                        @endif
                    </span>
                </div>
                <div class="confirm-row">
                    <span class="confirm-label">
                        <i class="fas fa-money-bill me-1 text-primary"></i> Total Spent
                    </span>
                    <span class="confirm-value" style="color:#10b981">
                        KSh {{ number_format(
                            \App\Models\BreakdownRequest::where('user_id',$user->id)
                                ->where('status','completed')->sum('price')
                        ) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Requests --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    {{-- <i class="fas fa-clipboard-list me-2 text-primary"></i> --}}
                    Request History
                </h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $requests->total() }} total
                </span>
            </div>
            <div class="fixgo-card-body p-0">
                @if($requests->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="font-size:12px;color:#6b7280;
                                           font-weight:600;padding:12px 20px">
                                    SERVICE
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    MECHANIC
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
                            @foreach($requests as $req)
                            <tr>
                                <td style="padding:12px 20px">
                                    <div style="font-weight:600;font-size:13px;color:#1a3c6e">
                                        {{ $req->serviceCategory->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:11px;color:#6b7280">
                                        {{ $req->request_number }}
                                    </div>
                                </td>
                                <td style="font-size:13px">
                                    {{ $req->mechanic->user->name ?? '—' }}
                                </td>
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
                <div class="p-3 d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                    <p class="text-muted">This user has no requests yet.</p>
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
    padding: 10px 0;
    border-bottom: 1px solid #f0f4ff;
    font-size: 13px;
}
.confirm-row:last-child { border-bottom: none; }
.confirm-label { color: #6b7280; }
.confirm-value { font-weight: 600; color: #1a3c6e; text-align: right; }
</style>
@endpush