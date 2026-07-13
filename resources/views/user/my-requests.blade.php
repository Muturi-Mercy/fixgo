@extends('layouts.app')

@section('title', 'My Requests')
@section('page-title', 'My Requests')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('user.request-assistance') }}" class="nav-link">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="{{ route('user.my-requests') }}" class="nav-link active">
        <i class="fas fa-list"></i> My Requests
    </a>
    <a href="{{ route('user.mechanics') }}" class="nav-link">
        <i class="fas fa-search"></i> Find Mechanics
    </a>
    <a href="{{ route('user.favourites') }}" class="nav-link">
        <i class="fas fa-heart"></i> Favourites
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-wallet"></i> Wallet
    </a>
    <a href="{{ route('user.notifications') }}" class="nav-link">
        <i class="fas fa-bell"></i> Notifications
        <span class="nav-badge">3</span>
    </a>
    <a href="{{ route('user.profile') }}" class="nav-link">
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

{{-- Header --}}
{{-- <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e; font-weight:700; margin:0">
            <i class="fas fa-list me-2 text-primary"></i> My Requests
        </h4>
        <p class="text-muted mb-0" style="font-size:14px">
            Track and manage all your breakdown requests.
        </p>
    </div>
    <a href="{{ route('user.request-assistance') }}"
       class="btn btn-fixgo" style="width:auto; padding:10px 20px">
        <i class="fas fa-plus me-2"></i> New Request
    </a>
</div> --}}

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
         style="border-radius:12px; border:none; background:#d1fae5; color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter Tabs --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body" style="padding:12px 20px">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('user.my-requests') }}"
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list me-1"></i> All
            </a>
            <a href="{{ route('user.my-requests', ['status' => 'pending']) }}"
               class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock me-1"></i> Pending
            </a>
            <a href="{{ route('user.my-requests', ['status' => 'accepted']) }}"
               class="filter-tab {{ request('status') == 'accepted' ? 'active' : '' }}">
                <i class="fas fa-check me-1"></i> Accepted
            </a>
            <a href="{{ route('user.my-requests', ['status' => 'on_the_way']) }}"
               class="filter-tab {{ request('status') == 'on_the_way' ? 'active' : '' }}">
                <i class="fas fa-truck me-1"></i> On The Way
            </a>
            <a href="{{ route('user.my-requests', ['status' => 'completed']) }}"
               class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
                <i class="fas fa-check-double me-1"></i> Completed
            </a>
            <a href="{{ route('user.my-requests', ['status' => 'cancelled']) }}"
               class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times me-1"></i> Cancelled
            </a>
        </div>
    </div>
</div>

{{-- Requests List --}}
@if($requests->count())
    <div class="row g-3">
        @foreach($requests as $req)
        <div class="col-12">
            <div class="fixgo-card request-card">
                <div class="fixgo-card-body">
                    <div class="row align-items-center">

                        {{-- Service Icon --}}
                        <div class="col-auto">
                            <div class="stat-icon blue" style="width:50px;height:50px;font-size:20px">
                                <i class="{{ getServiceIcon($req->serviceCategory->name ?? '') }}"></i>
                            </div>
                        </div>

                        {{-- Request Info --}}
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 style="font-weight:700;color:#1a3c6e;margin:0">
                                    {{ $req->serviceCategory->name ?? 'N/A' }}
                                </h6>
                                <span class="status-badge badge-{{ str_replace('_','-',$req->status) }}">
                                    {{ ucwords(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </div>
                            <div class="text-muted" style="font-size:12px">
                                <i class="fas fa-hashtag me-1"></i>{{ $req->request_number }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-car me-1"></i>
                                {{ $req->vehicleCategory->name ?? 'N/A' }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $req->created_at->format('M d, Y h:i A') }}
                            </div>
                            @if($req->user_address)
                            <div class="text-muted mt-1" style="font-size:12px">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                {{ Str::limit($req->user_address, 60) }}
                            </div>
                            @endif
                        </div>

                        {{-- Mechanic Info --}}
                        <div class="col-md-3 d-none d-md-block">
                            @if($req->mechanic)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="nav-user-avatar"
                                         style="width:34px;height:34px;font-size:14px">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:#1a3c6e">
                                            {{ $req->mechanic->user->name }}
                                        </div>
                                        <div style="font-size:11px;color:#f97316">
                                            <i class="fas fa-star"></i>
                                            {{ $req->mechanic->rating }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted" style="font-size:13px">
                                    <i class="fas fa-hourglass-half me-1"></i>
                                    Awaiting mechanic
                                </span>
                            @endif
                        </div>

                        {{-- Price --}}
                        <div class="col-auto d-none d-md-block">
                            <div style="font-weight:700;color:#1a3c6e;font-size:15px">
                                {{ $req->price ? 'KSh '.number_format($req->price) : '—' }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                {{-- Track button for active requests --}}
                                @if(in_array($req->status, ['accepted','on_the_way','arrived','repairing']))
                                    <a href="{{ route('user.track', $req->id) }}"
                                       class="btn btn-sm btn-fixgo"
                                       style="width:auto;padding:7px 14px">
                                        <i class="fas fa-map-marker-alt me-1"></i> Track
                                    </a>
                                @endif

                                {{-- View Details --}}
                                <button class="btn btn-sm btn-outline-primary"
                                        onclick="showRequestDetails({{ $req->id }})">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>

                                {{-- Cancel button for pending requests --}}
                                @if($req->status === 'pending')
                                    <form method="POST"
                                          action="{{ route('user.cancel-request', $req->id) }}"
                                          onsubmit="return confirm('Cancel this request?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Rate button for completed requests --}}
                                @if($req->status === 'completed' && !$req->rating)
                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="showRateModal({{ $req->id }})">
                                        <i class="fas fa-star me-1"></i> Rate
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $requests->links() }}
    </div>

@else
    {{-- Empty State --}}
    <div class="fixgo-card">
        <div class="fixgo-card-body text-center py-5">
            <i class="fas fa-clipboard fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Requests Found</h5>
            <p class="text-muted mb-4">
                {{ request('status') ? 'No '.request('status').' requests found.' : "You haven't made any requests yet." }}
            </p>
            <a href="{{ route('user.request-assistance') }}"
               class="btn btn-fixgo" style="width:auto;padding:12px 30px">
               <span style="color: white"> <i class="fas fa-plus me-2"></i> Make Your First Request</span>
            </a>
        </div>
    </div>
@endif

{{-- Request Details Modal --}}
<div class="modal fade" id="requestDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i> Request Details
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="requestDetailsBody">
                <div class="text-center py-3">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Rate Modal --}}
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i> Rate Your Mechanic
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('user.rate-request') }}" id="rateForm">
                    @csrf
                    <input type="hidden" name="request_id" id="rateRequestId">

                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">How was your experience?</p>
                        <div class="star-rating" id="starRating">
                            <i class="fas fa-star star" data-value="1"></i>
                            <i class="fas fa-star star" data-value="2"></i>
                            <i class="fas fa-star star" data-value="3"></i>
                            <i class="fas fa-star star" data-value="4"></i>
                            <i class="fas fa-star star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Leave a Review</label>
                        <textarea name="review" class="form-control" rows="3"
                                  placeholder="Share your experience..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-fixgo">
                        <i class="fas fa-paper-plane me-2"></i> Submit Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.filter-tab {
    padding: 7px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-decoration: none;
    background: #f3f4f6;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}
.filter-tab:hover {
    background: #eff6ff;
    color: #1a3c6e;
}
.filter-tab.active {
    background: linear-gradient(135deg, #1a3c6e, #3b82f6);
    color: white;
}
.request-card {
    transition: all 0.3s ease;
    border: 1px solid #f0f4ff;
}
.request-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border-color: #3b82f6;
}
.star-rating .star {
    font-size: 32px;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s ease;
}
.star-rating .star.active,
.star-rating .star:hover {
    color: #f59e0b;
}
</style>
@endpush

@push('scripts')
<script>
function showRequestDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
    document.getElementById('requestDetailsBody').innerHTML =
        '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>';
    modal.show();

    fetch(`/user/request-details/${id}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('requestDetailsBody').innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="confirm-box">
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-hashtag me-2 text-primary"></i>Request No.</span>
                                <span class="confirm-value">${data.request_number}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-tools me-2 text-primary"></i>Service</span>
                                <span class="confirm-value">${data.service}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-car me-2 text-primary"></i>Vehicle</span>
                                <span class="confirm-value">${data.vehicle}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-info me-2 text-primary"></i>Status</span>
                                <span class="confirm-value">${data.status}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-money-bill me-2 text-primary"></i>Price</span>
                                <span class="confirm-value">${data.price}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="confirm-box">
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Location</span>
                                <span class="confirm-value">${data.address}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-comment me-2 text-primary"></i>Problem</span>
                                <span class="confirm-value">${data.problem}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-user me-2 text-primary"></i>Mechanic</span>
                                <span class="confirm-value">${data.mechanic}</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label"><i class="fas fa-calendar me-2 text-primary"></i>Date</span>
                                <span class="confirm-value">${data.date}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
}

function showRateModal(requestId) {
    document.getElementById('rateRequestId').value = requestId;
    const modal = new bootstrap.Modal(document.getElementById('rateModal'));
    modal.show();
}

// Star rating
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('ratingValue').value = value;
        document.querySelectorAll('.star').forEach(s => {
            s.classList.toggle('active', s.getAttribute('data-value') <= value);
        });
    });
});
</script>
@endpush