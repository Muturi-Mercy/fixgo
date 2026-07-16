@extends('layouts.app')

@section('title', 'Reviews & Ratings')
@section('page-title', 'Reviews & Ratings')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

{{-- <div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        <i class="fas fa-star me-2 text-warning"></i> Reviews & Ratings
    </h4>
</div> --}}

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($avgRating ?? 0, 1) }}</h3>
                <p>Average Rating</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-comments"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalReviews) }}</h3>
                <p>Total Reviews</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-thumbs-up"></i></div>
            <div class="stat-info">
                <h3>
                    {{ \App\Models\RatingReview::where('rating', '>=', 4)->count() }}
                </h3>
                <p>Positive (4-5 Stars)</p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($reviews->count())
            @foreach($reviews as $review)
            <div class="notification-item">
                <div class="d-flex align-items-start gap-3">
                    <div class="nav-user-avatar"
                         style="width:42px;height:42px;font-size:16px;flex-shrink:0">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span style="font-weight:700;font-size:14px;color:#1a3c6e">
                                    {{ $review->user->name ?? 'User' }}
                                </span>
                                <span style="font-size:12px;color:#6b7280;margin-left:8px">
                                    → {{ $review->mechanic->user->name ?? 'Mechanic' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:11px;color:#9ca3af">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                                <form method="POST"
                                      action="{{ route('admin.reviews.delete', $review->id) }}"
                                      onsubmit="return confirm('Delete this review?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            style="padding:3px 8px;font-size:11px">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div style="color:#f59e0b;margin:4px 0">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star
                                   {{ $i <= $review->rating ? '' : 'text-muted' }}"
                                   style="font-size:13px"></i>
                            @endfor
                            <span style="font-size:12px;color:#6b7280;margin-left:6px">
                                {{ $review->rating }}/5
                            </span>
                        </div>
                        @if($review->review)
                        <p style="font-size:13px;color:#374151;margin:0;
                                  padding:8px 12px;background:#f8fafc;
                                  border-radius:8px;border-left:3px solid #3b82f6">
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
        </div>
        @endif
    </div>
</div>

@endsection
