@extends('layouts.app')

@section('title', 'My Portfolio')
@section('page-title', 'My Portfolio')

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            {{--<i class="fas fa-images me-2 text-primary"></i> My Portfolio--}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
            Showcase your repair work to attract more customers.
        </p>
    </div>
    <a href="{{ route('mechanic.portfolio.create') }}"
       class="btn btn-fixgo" style="width:auto;padding:10px 20px">
        <span style="color: white"><i class="fas fa-plus me-2"></i> Add Work</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Category Filter --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-header">
        <h6><i class="fas fa-filter me-2 text-primary"></i>Filter by Category</h6>
        <span class="text-muted" style="font-size:13px">
            <span id="portfolioCount">{{ $portfolios->count() }}</span> works
        </span>
    </div>
    <div class="fixgo-card-body">
        <div class="portfolio-filter-grid">

            <button class="portfolio-filter-btn active" onclick="filterPortfolio('All', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#1a3c6e,#3b82f6)">
                    <i class="fas fa-th"></i>
                </div>
                <span class="pf-label">All Work</span>
                <span class="pf-count">{{ $portfolios->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Engine', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#f97316,#ef4444)">
                    <i class="fas fa-cogs"></i>
                </div>
                <span class="pf-label">Engine</span>
                <span class="pf-count">{{ $portfolios->where('category','Engine')->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Electrical', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="pf-label">Electrical</span>
                <span class="pf-count">{{ $portfolios->where('category','Electrical')->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Brakes', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                    <i class="fas fa-circle-notch"></i>
                </div>
                <span class="pf-label">Brakes</span>
                <span class="pf-count">{{ $portfolios->where('category','Brakes')->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Tyres', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#6b7280,#374151)">
                    <i class="fas fa-circle"></i>
                </div>
                <span class="pf-label">Tyres</span>
                <span class="pf-count">{{ $portfolios->where('category','Tyres')->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Bodywork', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                    <i class="fas fa-car"></i>
                </div>
                <span class="pf-label">Bodywork</span>
                <span class="pf-count">{{ $portfolios->where('category','Bodywork')->count() }}</span>
            </button>

            <button class="portfolio-filter-btn" onclick="filterPortfolio('Others', this)">
                <div class="pf-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                    <i class="fas fa-wrench"></i>
                </div>
                <span class="pf-label">Others</span>
                <span class="pf-count">{{ $portfolios->where('category','Others')->count() }}</span>
            </button>

        </div>
    </div>
</div>

@if($portfolios->count())
    <div class="row g-4" id="portfolioContainer">
        @foreach($portfolios as $portfolio)
        <div class="col-md-6 portfolio-item" data-category="{{ $portfolio->category }}">
            <div class="fixgo-card" style="cursor:pointer"
                onclick="window.location='{{ route('mechanic.portfolio.view', $portfolio->id) }}'">
                <div class="fixgo-card-header">
                    <div>
                        <h6 style="font-weight:700;color:#1a3c6e;margin:0">
                            {{ $portfolio->title }}
                        </h6>
                        <span style="font-size:12px;color:#6b7280">
                            @if($portfolio->work_date)
                                {{ \Carbon\Carbon::parse($portfolio->work_date)->format('M d, Y') }}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-badge badge-accepted">
                            {{ $portfolio->category }}
                        </span>
                        <form method="POST"
                              action="{{ route('mechanic.portfolio.delete', $portfolio->id) }}"
                              onsubmit="return confirm('Delete this portfolio item?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    style="padding:4px 10px">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="fixgo-card-body">
                    @if($portfolio->description)
                    <p style="font-size:14px;color:#6b7280;margin-bottom:16px">
                        {{ $portfolio->description }}
                    </p>
                    @endif

                    @if($portfolio->images->count())
                    <div class="row g-2">
                        @foreach($portfolio->images as $image)
                        <div class="col-4">
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="w-100 rounded"
                                     style="height:100px;object-fit:cover;cursor:pointer"
                                     onclick="openImageModal('{{ asset('storage/'.$image->image_path) }}')">
                                @if($image->type !== 'general')
                                <span style="position:absolute;bottom:4px;left:4px;
                                             background:rgba(0,0,0,0.7);color:white;
                                             padding:2px 6px;border-radius:4px;
                                             font-size:10px;font-weight:600">
                                    {{ ucfirst($image->type) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="fixgo-card">
        <div class="fixgo-card-body text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-4"></i>
            <h5 style="color:#1a3c6e;font-weight:700">No Portfolio Work Yet</h5>
            <p class="text-muted mb-4">
                Add photos of your completed repairs to showcase your skills.
            </p>
            <a href="{{ route('mechanic.portfolio.create') }}"
               class="btn btn-fixgo" style="width:auto;padding:12px 30px">
                <i class="fas fa-plus me-2"></i> Add Your First Work
            </a>
        </div>
    </div>
@endif

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content"
             style="background:transparent;border:none;box-shadow:none">
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" class="img-fluid rounded"
                     style="max-height:80vh">
                <button type="button" class="btn-close btn-close-white mt-3"
                        data-bs-dismiss="modal"
                        style="filter:invert(1)"></button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.portfolio-filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
}

.portfolio-filter-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 16px 10px;
    background: #f8fafc;
    border: 2px solid #f0f4ff;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.portfolio-filter-btn:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.12);
}

.portfolio-filter-btn.active {
    border-color: #1a3c6e;
    background: #eff6ff;
    box-shadow: 0 6px 20px rgba(26,60,110,0.15);
}

.portfolio-filter-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 3px;
    background: #f97316;
    border-radius: 3px;
}

.pf-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

.portfolio-filter-btn:hover .pf-icon,
.portfolio-filter-btn.active .pf-icon {
    transform: scale(1.1);
}

.pf-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.portfolio-filter-btn.active .pf-label {
    color: #1a3c6e;
}

.pf-count {
    background: #e5e7eb;
    color: #6b7280;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
    min-width: 24px;
    text-align: center;
}

.portfolio-filter-btn.active .pf-count {
    background: #1a3c6e;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
function filterPortfolio(category, btn) {
    document.querySelectorAll('.portfolio-filter-btn').forEach(b =>
        b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.portfolio-item');
    let visible = 0;

    items.forEach(item => {
        if (category === 'All' || item.dataset.category === category) {
            item.style.display = '';
            visible++;
        } else {
            item.style.display = 'none';
        }
    });

    document.getElementById('portfolioCount').textContent = visible;
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush