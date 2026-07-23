@extends('layouts.app')

@section('title', 'Add Portfolio Work')
@section('page-title', 'Add Portfolio Work')

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
    @if(auth()->user()->unreadNotifications->count())
        <span class="nav-badge" id="sidebarNotifBadge">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
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
    <a href="{{ route('mechanic.portfolio') }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Portfolio
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Add New Portfolio Work
                </h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('mechanic.portfolio.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Title --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-heading me-1 text-primary"></i> Work Title
                            </label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Engine Overhaul" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-tag me-1 text-primary"></i> Category
                            </label>
                            <select name="category"
                                    class="form-select form-control
                                           @error('category') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Category --</option>
                                <option value="Engine">Engine</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Brakes">Brakes</option>
                                <option value="Tyres">Tyres</option>
                                <option value="Bodywork">Bodywork</option>
                                <option value="Others">Others</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Work Date --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                Date of Work
                            </label>
                            <input type="date" name="work_date"
                                   class="form-control"
                                   value="{{ old('work_date') }}">
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-comment me-1 text-primary"></i>
                                Description
                            </label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Describe the work done...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Images Upload --}}
                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-images me-1 text-primary"></i>
                                Upload Images
                            </label>
                            <div class="photo-upload-area"
                                 onclick="document.getElementById('portfolioImages').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0" style="font-size:14px">
                                    Click to upload work photos
                                </p>
                                <p class="text-muted mb-0" style="font-size:12px">
                                    PNG, JPG up to 3MB each — you can select multiple
                                </p>
                            </div>
                            <input type="file" id="portfolioImages"
                                   name="images[]" multiple accept="image/*"
                                   class="d-none" onchange="previewImages(this)">
                        </div>

                        {{-- Image Type Selector --}}
                        <div class="col-12" id="imageTypeSection" style="display:none">
                            <label class="form-label fw-bold" style="font-size:13px">
                                Tag Each Image
                            </label>
                            <div id="imagePreviewContainer" class="row g-3"></div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-fixgo"
                                    style="width:auto;padding:11px 30px">
                                <span style="color: white"><i class="fas fa-save me-2"></i> Save Portfolio Work</span>
                            </button>
                            <a href="{{ route('mechanic.portfolio') }}"
                               class="btn btn-outline-secondary"
                               style="padding:11px 24px">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewImages(input) {
    const container = document.getElementById('imagePreviewContainer');
    const section = document.getElementById('imageTypeSection');
    container.innerHTML = '';

    if (input.files.length > 0) {
        section.style.display = 'block';
    }

    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            container.innerHTML += `
                <div class="col-md-4">
                    <div class="fixgo-card">
                        <img src="${e.target.result}"
                             class="w-100"
                             style="height:120px;object-fit:cover;
                                    border-radius:14px 14px 0 0">
                        <div class="p-2">
                            <select name="image_types[]"
                                    class="form-select form-control"
                                    style="font-size:12px;padding:5px 8px">
                                <option value="general">General</option>
                                <option value="before">Before</option>
                                <option value="after">After</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush