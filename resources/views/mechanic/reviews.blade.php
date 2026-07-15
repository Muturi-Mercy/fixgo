@extends('layouts.app')

@section('title', 'Reviews')
@section('page-title', 'Reviews & Ratings')

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
    <a href="{{ route('mechanic.earnings') }}" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link active">
        <i class="fas fa-star"></i> Reviews
    </a>
    <a href="{{ route('mechanic.profile') }}" class="nav-link">
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

<div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        {{--<i class="fas fa-star me-2 text-warning"></i> Reviews & Ratings--}}
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        See what customers say about your work.
    </p>
</div>

{{-- Rating Overview --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="fixgo-card text-center">
            <div class="fixgo-card-body py-4">
                <h1 style="font-size:56px;font-weight:800;color:#1a3c6e;margin:0">
                    {{ number_format($avgRating ?? 0, 1) }}
                </h1>
                <div class="my-2">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star fa-lg
                           {{ $i <= round($avgRating ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                </div>
                <p class="text-muted mb-0" style="font-size:14px">
                    Based on {{ $reviews->total() }} reviews
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="fixgo-card h-100">
            <div class="fixgo-card-body">
                <p class="fw-bold mb-3" style="font-size:14px;color:#1a3c6e">
                    Rating Breakdown
                </p>
                @for($star=5;$star>=1;$star--)
                @php
                    $count = $reviews->getCollection()
                        ->where('rating', $star)->count();
                    $total = $reviews->total() ?: 1;
                    $percent = ($count / $total) * 100;
                @endphp
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size:13px;color:#6b7280;width:30px">
                        {{ $star }} <i class="fas fa-star text-warning" style="font-size:11px"></i>
                    </span>
                    <div class="flex-1" style="background:#f0f4ff;
                                               border-radius:10px;height:10px;overflow:hidden">
                        <div style="width:{{ $percent }}%;height:100%;
                                    background:linear-gradient(90deg,#f59e0b,#f97316);
                                    border-radius:10px;transition:width 0.5s ease">
                        </div>
                    </div>
                    <span style="font-size:13px;color:#6b7280;width:20px">
                        {{ $count }}
                    </span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

{{-- Reviews List --}}
<div class="fixgo-card">
    <div class="fixgo-card-header">
        <h6><i class="fas fa-comments me-2 text-primary"></i>Customer Reviews</h6>
    </div>
    <div class="fixgo-card-body p-0">
        @if($reviews->count())
            @foreach($reviews as $review)
            <div class="notification-item">
                <div class="d-flex gap-3">
                    <div class="nav-user-avatar"
                         style="width:46px;height:46px;font-size:18px;flex-shrink:0">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <span style="font-weight:700;font-size:14px;color:#1a3c6e">
                                    {{ $review->user->name ?? 'Anonymous' }}
                                </span>
                            </div>
                            <span style="font-size:12px;color:#9ca3af">
                                {{ $review->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="d-flex gap-1 mb-2">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"
                                   style="font-size:14px"></i>
                            @endfor
                            <span style="font-size:13px;font-weight:600;
                                         color:#1a3c6e;margin-left:6px">
                                {{ $review->rating }}/5
                            </span>
                        </div>
                        @if($review->review)
                        <p style="font-size:14px;color:#374151;margin:0;
                                  line-height:1.6;background:#f8fafc;
                                  padding:10px 14px;border-radius:10px;
                                  border-left:3px solid #3b82f6">
                            "{{ $review->review }}"
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <div class="p-3 d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-4"></i>
                <h5 style="color:#1a3c6e;font-weight:700">No Reviews Yet</h5>
                <p class="text-muted">
                    Complete jobs to start receiving customer reviews.
                </p>
            </div>
        @endif
    </div>
</div>

@endsection