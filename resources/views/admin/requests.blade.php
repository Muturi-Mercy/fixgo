@extends('layouts.app')

@section('title', 'Manage Requests')
@section('page-title', 'Manage Requests')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        <i class="fas fa-clipboard-list me-2 text-primary"></i> Manage Requests
    </h4>
    <p class="text-muted mb-0" style="font-size:14px">
        View and monitor all breakdown requests.
    </p>
</div>

{{-- Filter --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body">
        <form method="GET" action="{{ route('admin.requests') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search request number..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-control">
                        <option value="">All Status</option>
                        @foreach(['pending','accepted','on_the_way','arrived','repairing','completed','cancelled'] as $s)
                        <option value="{{ $s }}"
                            {{ request('status')===$s?'selected':'' }}>
                            {{ ucwords(str_replace('_',' ',$s)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-fixgo w-100"
                            style="padding:11px">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($requests->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:12px;color:#6b7280;
                                   font-weight:600;padding:12px 20px">REQUEST</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">USER</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">MECHANIC</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">DATE</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">AMOUNT</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600;font-size:13px;color:#1a3c6e">
                                {{ $req->serviceCategory->name ?? 'N/A' }}
                            </div>
                            <div style="font-size:11px;color:#6b7280">
                                {{ $req->request_number }}
                            </div>
                        </td>
                        <td style="font-size:13px">{{ $req->user->name ?? '—' }}</td>
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
                        <td>
                            <a href="{{ route('admin.requests.view', $req->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               style="padding:5px 12px;font-size:12px">
                                <i class="fas fa-eye"></i>
                            </a>
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
            <i class="fas fa-clipboard fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Requests Found</h5>
        </div>
        @endif
    </div>
</div>

@endsection