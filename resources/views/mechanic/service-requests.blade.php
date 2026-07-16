@extends('layouts.app')

@section('title', 'Service Requests')
@section('page-title', 'Service Requests')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link active">
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

<div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        {{-- <i class="fas fa-bell me-2 text-warning"></i> Service Requests --}}
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        Manage incoming and active service requests.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Tabs --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body" style="padding:10px 16px">
        <ul class="nav nav-pills gap-2" id="requestTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#new" data-bs-toggle="tab">
                    <i class="fas fa-bell me-1"></i> New
                    <span class="badge bg-danger ms-1">{{ $newRequests->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#accepted" data-bs-toggle="tab">
                    <i class="fas fa-check me-1"></i> Accepted
                    <span class="badge bg-primary ms-1">{{ $acceptedRequests->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#completed" data-bs-toggle="tab">
                    <i class="fas fa-flag-checkered me-1"></i> Completed
                    <span class="badge bg-success ms-1">{{ $completedRequests->count() }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content">

    {{-- NEW REQUESTS --}}
    <div class="tab-pane fade show active" id="new">
        @if($newRequests->count())
            <div class="row g-3">
                @foreach($newRequests as $req)
                <div class="col-md-6">
                    <div class="fixgo-card request-card-mechanic">
                        <div class="fixgo-card-body">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="stat-icon orange"
                                         style="width:46px;height:46px;font-size:18px;flex-shrink:0">
                                        <i class="{{ getServiceIcon($req->serviceCategory->name ?? '') }}"></i>
                                    </div>
                                    <div>
                                        <h6 style="font-weight:700;color:#1a3c6e;margin:0">
                                            {{ $req->serviceCategory->name ?? 'N/A' }}
                                        </h6>
                                        <span style="font-size:12px;color:#6b7280">
                                            {{ $req->request_number }}
                                        </span>
                                    </div>
                                </div>
                                <span class="status-badge badge-pending">New</span>
                            </div>

                            {{-- Customer Info --}}
                            <div class="p-3 mb-3"
                                 style="background:#f8fafc;border-radius:10px">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-user text-primary" style="width:16px"></i>
                                    <span style="font-size:13px;font-weight:600">
                                        {{ $req->user->name }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-car text-primary" style="width:16px"></i>
                                    <span style="font-size:13px">
                                        {{ $req->vehicleCategory->name ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <i class="fas fa-map-marker-alt text-danger mt-1"
                                       style="width:16px"></i>
                                    <span style="font-size:13px;color:#6b7280">
                                        {{ \Illuminate\Support\Str::limit($req->user_address ?? 'Location shared on map', 50) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-comment text-primary mt-1"
                                       style="width:16px"></i>
                                    <span style="font-size:13px;color:#6b7280">
                                        {{ \Illuminate\Support\Str::limit($req->problem_description, 60) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Time & Distance --}}
                            <div class="d-flex justify-content-between mb-3">
                                <span style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $req->created_at->diffForHumans() }}
                                </span>
                                <span style="font-size:12px;color:#6b7280">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                    Nearby
                                </span>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-2">
                                <form method="POST"
                                      action="{{ route('mechanic.accept-request', $req->id) }}"
                                      class="flex-1">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-success w-100"
                                            style="border-radius:10px;font-weight:600">
                                        <span style="color: white"><i class="fas fa-check me-2"></i> Accept</span>
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('mechanic.decline-request', $req->id) }}"
                                      class="flex-1">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-outline-danger w-100"
                                            style="border-radius:10px;font-weight:600">
                                        <i class="fas fa-times me-2"></i> Decline
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="fixgo-card">
                <div class="fixgo-card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                    <h5 style="color:#1a3c6e;font-weight:700">No New Requests</h5>
                    <p class="text-muted">No incoming service requests at the moment.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ACCEPTED REQUESTS --}}
    <div class="tab-pane fade" id="accepted">
        @if($acceptedRequests->count())
            <div class="row g-3">
                @foreach($acceptedRequests as $req)
                <div class="col-md-6">
                    <div class="fixgo-card">
                        <div class="fixgo-card-body">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="stat-icon blue"
                                        style="width:46px;height:46px;font-size:18px;flex-shrink:0">
                                        <i class="{{ getServiceIcon($req->serviceCategory->name ?? '') }}"></i>
                                    </div>
                                    <div>
                                        <h6 style="font-weight:700;color:#1a3c6e;margin:0">
                                            {{ $req->serviceCategory->name ?? 'N/A' }}
                                        </h6>
                                        <span style="font-size:12px;color:#6b7280">
                                            {{ $req->request_number }}
                                        </span>
                                    </div>
                                </div>
                                <span class="status-badge badge-{{ str_replace('_','-',$req->status) }}">
                                    {{ ucwords(str_replace('_',' ',$req->status)) }}
                                </span>
                            </div>

                            {{-- Customer Info --}}
                            <div class="p-3 mb-3"
                                style="background:#f8fafc;border-radius:10px">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-user text-primary" style="width:16px"></i>
                                    <span style="font-size:13px;font-weight:600">
                                        {{ $req->user->name }}
                                    </span>
                                    <a href="tel:{{ $req->user->phone }}"
                                    class="btn btn-sm btn-outline-success ms-auto"
                                    style="padding:3px 10px;font-size:11px">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                </div>
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <i class="fas fa-map-marker-alt text-danger mt-1"
                                    style="width:16px"></i>
                                    <span style="font-size:13px;color:#6b7280">
                                        {{ \Illuminate\Support\Str::limit($req->user_address ?? 'Location on map', 50) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-comment text-primary mt-1"
                                    style="width:16px"></i>
                                    <span style="font-size:13px;color:#6b7280">
                                        {{ \Illuminate\Support\Str::limit($req->problem_description, 50) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Current Charge --}}
                            @if($req->price)
                            <div class="d-flex align-items-center gap-2 mb-3 p-2"
                                style="background:#d1fae5;border-radius:8px">
                                <i class="fas fa-tag text-success"></i>
                                <span style="font-size:13px;font-weight:700;color:#065f46">
                                    Charge: KSh {{ number_format($req->price) }}
                                </span>
                            </div>
                            @endif

                            {{-- Update Status Form with Price --}}
                            <form method="POST"
                                action="{{ route('mechanic.update-request-status', $req->id) }}">
                                @csrf @method('PATCH')

                                {{-- Set/Update Price --}}
                                <div class="mb-2">
                                    <label style="font-size:12px;font-weight:700;
                                                color:#374151;margin-bottom:4px;display:block">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        Set Charge (KSh)
                                    </label>
                                    <input type="number"
                                        name="price"
                                        class="form-control"
                                        style="font-size:13px;padding:8px 12px"
                                        value="{{ $req->price }}"
                                        placeholder="e.g. 1500"
                                        min="0">
                                </div>

                                {{-- Status Selector --}}
                                <div class="d-flex gap-2 mb-3">
                                    <select name="status"
                                            class="form-select form-control"
                                            style="font-size:13px">
                                        <option value="accepted"
                                            {{ $req->status==='accepted'?'selected':'' }}>
                                            Accepted
                                        </option>
                                        <option value="on_the_way"
                                            {{ $req->status==='on_the_way'?'selected':'' }}>
                                            On The Way
                                        </option>
                                        <option value="arrived"
                                            {{ $req->status==='arrived'?'selected':'' }}>
                                            Arrived
                                        </option>
                                        <option value="repairing"
                                            {{ $req->status==='repairing'?'selected':'' }}>
                                            Repairing
                                        </option>
                                        <option value="completed"
                                            {{ $req->status==='completed'?'selected':'' }}>
                                            Completed
                                        </option>
                                    </select>
                                    <button type="submit"
                                            class="btn btn-fixgo"
                                            style="width:auto;padding:8px 16px;white-space:nowrap">
                                        <span style="color: white"><i class="fas fa-save me-1"></i> Update</span>
                                    </button>
                                </div>
                            </form>

                            {{-- Chat Button --}}
                            <a href="{{ route('mechanic.chat', $req->id) }}"
                            class="btn btn-outline-primary w-100"
                            style="border-radius:10px;font-weight:600">
                                <i class="fas fa-comment me-2"></i> Chat with Customer
                            </a>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="fixgo-card">
                <div class="fixgo-card-body text-center py-5">
                    <i class="fas fa-clipboard-check fa-4x text-muted mb-4"></i>
                    <h5 style="color:#1a3c6e;font-weight:700">No Active Requests</h5>
                    <p class="text-muted">You have no accepted requests currently.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- COMPLETED REQUESTS --}}
    <div class="tab-pane fade" id="completed">
        @if($completedRequests->count())
        <div class="fixgo-card">
            <div class="fixgo-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="font-size:12px;color:#6b7280;
                                           font-weight:600;padding:12px 20px">
                                    SERVICE
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    CUSTOMER
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    COMPLETED
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    AMOUNT
                                </th>
                                <th style="font-size:12px;color:#6b7280;font-weight:600">
                                    RATING
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completedRequests as $req)
                            <tr>
                                <td style="padding:14px 20px">
                                    <div style="font-weight:600;font-size:14px">
                                        {{ $req->serviceCategory->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:12px;color:#6b7280">
                                        {{ $req->request_number }}
                                    </div>
                                </td>
                                <td style="font-size:13px">{{ $req->user->name }}</td>
                                <td style="font-size:13px;color:#6b7280">
                                    {{ $req->completed_at ?
                                       \Carbon\Carbon::parse($req->completed_at)->format('M d, Y') :
                                       $req->updated_at->format('M d, Y') }}
                                </td>
                                <td style="font-weight:600;color:#1a3c6e">
                                    {{ $req->price ? 'KSh '.number_format($req->price) : '—' }}
                                </td>
                                <td>
                                    @if($req->rating)
                                        <span style="color:#f59e0b;font-weight:600">
                                            <i class="fas fa-star"></i>
                                            {{ $req->rating->rating }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:12px">
                                            Not rated
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
            <div class="fixgo-card">
                <div class="fixgo-card-body text-center py-5">
                    <i class="fas fa-flag-checkered fa-4x text-muted mb-4"></i>
                    <h5 style="color:#1a3c6e;font-weight:700">No Completed Jobs</h5>
                    <p class="text-muted">Completed jobs will appear here.</p>
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@push('styles')
<style>
.request-card-mechanic {
    border: 1px solid #f0f4ff;
    transition: all 0.3s ease;
}
.request-card-mechanic:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border-color: #f97316;
}
</style>
@endpush

@push('scripts')
<script>
    // Auto-share location when status is on_the_way
document.querySelectorAll('select[name="status"]').forEach(select => {
    select.addEventListener('change', function() {
        if (this.value === 'on_the_way') {
            startSharingLocation();
        }
    });
});

let locationInterval;

function startSharingLocation() {
    if (navigator.geolocation) {
        // Share immediately
        shareLocation();
        // Then every 15 seconds
        locationInterval = setInterval(shareLocation, 15000);
        showToast('Live location sharing started', 'success');
    }
}

function shareLocation() {
    navigator.geolocation.getCurrentPosition(function(pos) {
        fetch('{{ route("mechanic.update-location") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude
            })
        });
    });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position:fixed;top:80px;right:20px;z-index:9999;
        background:${type === 'success' ? '#10b981' : '#ef4444'};
        color:white;padding:12px 20px;border-radius:12px;
        font-size:13px;font-weight:600;
        box-shadow:0 8px 25px rgba(0,0,0,0.2)`;
    toast.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush