@extends('layouts.app')

@section('title', $portfolio->title)
@section('page-title', 'Portfolio Work')

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
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link active">
        <i class="fas fa-images"></i> Portfolio
    </a>
    <a href="{{ route('mechanic.reviews') }}" class="nav-link">
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

{{-- Back & Actions --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('mechanic.portfolio') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Portfolio
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('mechanic.portfolio.edit', $portfolio->id) }}"
           class="btn btn-outline-primary" style="padding:9px 20px">
            <i class="fas fa-edit me-2"></i> Edit Work
        </a>
        <form method="POST"
              action="{{ route('mechanic.portfolio.delete', $portfolio->id) }}"
              onsubmit="return confirm('Delete this portfolio item?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"
                    style="padding:9px 20px">
                <i class="fas fa-trash me-2"></i> Delete
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

    {{-- Work Info --}}
    <div class="col-lg-4">
        <div class="fixgo-card">
            <div style="background:linear-gradient(135deg,#1a3c6e,#3b82f6);
                        padding:30px 20px;border-radius:14px 14px 0 0;text-align:center">
                <div style="width:70px;height:70px;background:rgba(255,255,255,0.2);
                            border-radius:20px;margin:0 auto 16px;
                            display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-wrench" style="font-size:30px;color:white"></i>
                </div>
                <h5 style="color:white;font-weight:700;margin:0">
                    {{ $portfolio->title }}
                </h5>
            </div>
            <div class="fixgo-card-body">
                <div class="confirm-row">
                    <span class="confirm-label">Category</span>
                    <span class="confirm-value">
                        <span class="status-badge badge-accepted">
                            {{ $portfolio->category }}
                        </span>
                    </span>
                </div>
                @if($portfolio->work_date)
                <div class="confirm-row">
                    <span class="confirm-label">Date</span>
                    <span class="confirm-value">
                        {{ \Carbon\Carbon::parse($portfolio->work_date)->format('M d, Y') }}
                    </span>
                </div>
                @endif
                <div class="confirm-row">
                    <span class="confirm-label">Photos</span>
                    <span class="confirm-value">{{ $portfolio->images->count() }} images</span>
                </div>

                @if($portfolio->description)
                <div class="mt-3 p-3"
                     style="background:#f8fafc;border-radius:10px">
                    <p style="font-size:13px;font-weight:600;
                               color:#6b7280;margin-bottom:6px">DESCRIPTION</p>
                    <p style="font-size:14px;color:#374151;margin:0;line-height:1.7">
                        {{ $portfolio->description }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Photos Gallery --}}
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-images me-2 text-primary"></i>Work Photos</h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $portfolio->images->count() }} photos
                </span>
            </div>
            <div class="fixgo-card-body">

                {{-- Before & After Section --}}
                @php
                    $beforeImages = $portfolio->images->where('type','before');
                    $afterImages  = $portfolio->images->where('type','after');
                    $generalImages = $portfolio->images->where('type','general');
                @endphp

                @if($beforeImages->count() && $afterImages->count())
                <div class="mb-4">
                    <p style="font-weight:700;color:#1a3c6e;
                               margin-bottom:12px;font-size:14px">
                        <i class="fas fa-exchange-alt me-2 text-primary"></i>
                        Before & After
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <p style="font-size:12px;font-weight:700;
                                       color:#ef4444;text-align:center;
                                       text-transform:uppercase;letter-spacing:1px">
                                Before
                            </p>
                            @foreach($beforeImages as $img)
                            <img src="{{ asset('storage/'.$img->image_path) }}"
                                 class="w-100 rounded mb-2"
                                 style="height:160px;object-fit:cover;cursor:pointer"
                                 onclick="openLightbox('{{ asset('storage/'.$img->image_path) }}')">
                            @endforeach
                        </div>
                        <div class="col-6">
                            <p style="font-size:12px;font-weight:700;
                                       color:#10b981;text-align:center;
                                       text-transform:uppercase;letter-spacing:1px">
                                After
                            </p>
                            @foreach($afterImages as $img)
                            <img src="{{ asset('storage/'.$img->image_path) }}"
                                 class="w-100 rounded mb-2"
                                 style="height:160px;object-fit:cover;cursor:pointer"
                                 onclick="openLightbox('{{ asset('storage/'.$img->image_path) }}')">
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- General Images --}}
                @if($generalImages->count())
                <div>
                    @if($beforeImages->count())
                    <p style="font-weight:700;color:#1a3c6e;
                               margin-bottom:12px;font-size:14px">
                        <i class="fas fa-images me-2 text-primary"></i>
                        Additional Photos
                    </p>
                    @endif
                    <div class="row g-2">
                        @foreach($generalImages as $img)
                        <div class="col-4">
                            <img src="{{ asset('storage/'.$img->image_path) }}"
                                 class="w-100 rounded"
                                 style="height:120px;object-fit:cover;cursor:pointer;
                                        transition:transform 0.2s ease"
                                 onmouseover="this.style.transform='scale(1.03)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onclick="openLightbox('{{ asset('storage/'.$img->image_path) }}')">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($portfolio->images->count() === 0)
                <div class="text-center py-4">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No photos uploaded yet.</p>
                    <a href="{{ route('mechanic.portfolio.edit', $portfolio->id) }}"
                       class="btn btn-outline-primary">
                        <i class="fas fa-upload me-2"></i> Upload Photos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content"
             style="background:rgba(0,0,0,0.9);border:none;border-radius:16px">
            <div class="modal-body p-2 text-center position-relative">
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        style="position:absolute;top:12px;right:12px;
                               z-index:10;filter:invert(1)"></button>
                <img id="lightboxImage" src="" class="img-fluid"
                     style="max-height:85vh;border-radius:12px">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>
@endpush