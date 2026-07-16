@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            {{--<i class="fas fa-users me-2 text-primary"></i> Manage Users--}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
            View and manage all registered drivers.
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Search & Filter --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body">
        <form method="GET" action="{{ route('admin.users') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name or email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-control">
                        <option value="">All Status</option>
                        <option value="active"
                            {{ request('status')=='active'?'selected':'' }}>Active</option>
                        <option value="suspended"
                            {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-fixgo w-100"
                            style="padding:11px">
                       <span style="color: white"><i class="fas fa-search me-1"></i> Search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($users->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:12px;color:#6b7280;
                                   font-weight:600;padding:12px 20px">#</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">USER</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">PHONE</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">REQUESTS</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">JOINED</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td style="padding:14px 20px;color:#6b7280;font-size:13px">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="nav-user-avatar"
                                     style="width:36px;height:36px;font-size:14px;flex-shrink:0">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                             style="width:100%;height:100%;
                                                    object-fit:cover;border-radius:50%">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;color:#1a3c6e">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:11px;color:#6b7280">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px">{{ $user->phone ?? '—' }}</td>
                        <td style="font-size:13px;font-weight:600;color:#1a3c6e">
                            {{ $user->breakdownRequests->count() }}
                        </td>
                        <td style="font-size:12px;color:#6b7280">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="status-badge badge-available">Active</span>
                            @else
                                <span class="status-badge badge-cancelled">Suspended</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.view', $user->id) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   style="padding:5px 12px;font-size:12px">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.users.toggle-status', $user->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            style="padding:5px 12px;font-size:12px"
                                            title="{{ $user->status === 'active' ? 'Suspend' : 'Activate' }}">
                                        <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.users.delete', $user->id) }}"
                                      onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            style="padding:5px 12px;font-size:12px">
                                        <i class="fas fa-trash"></i>
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
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Users Found</h5>
            <p class="text-muted">No users match your search.</p>
        </div>
        @endif
    </div>
</div>

@endsection