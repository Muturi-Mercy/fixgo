@extends('layouts.app')

@section('title', 'Edit Portfolio Work')
@section('page-title', 'Edit Portfolio Work')

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

<div class="mb-4">
    <a href="{{ route('mechanic.portfolio.view', $portfolio->id) }}"
       style="color:#3b82f6;text-decoration:none;font-size:14px">
        <i class="fas fa-arrow-left me-2"></i> Back to Work Details
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Edit: {{ $portfolio->title }}
                </h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST"
                      action="{{ route('mechanic.portfolio.update', $portfolio->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-4">

                        {{-- Title --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-heading me-1 text-primary"></i> Work Title
                            </label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $portfolio->title) }}" required>
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-tag me-1 text-primary"></i> Category
                            </label>
                            <select name="category" class="form-select form-control" required>
                                @foreach(['Engine','Electrical','Brakes','Tyres','Bodywork','Others'] as $cat)
                                <option value="{{ $cat }}"
                                    {{ $portfolio->category === $cat ? 'selected':'' }}>
                                    {{ $cat }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Work Date --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-calendar me-1 text-primary"></i> Date
                            </label>
                            <input type="date" name="work_date" class="form-control"
                                   value="{{ old('work_date', $portfolio->work_date) }}">
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:13px">
                                <i class="fas fa-comment me-1 text-primary"></i> Description
                            </label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $portfolio->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Existing Images --}}
                    @if($portfolio->images->count())
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:13px">
                            <i class="fas fa-images me-1 text-primary"></i>
                            Current Photos
                            <small class="text-muted fw-normal ms-2">
                                (Check to delete)
                            </small>
                        </label>
                        <div class="row g-3">
                            @foreach($portfolio->images as $image)
                            <div class="col-4 col-md-3">
                                <div class="position-relative existing-image-card">
                                    <img src="{{ asset('storage/'.$image->image_path) }}"
                                         class="w-100 rounded"
                                         style="height:100px;object-fit:cover">
                                    <span style="position:absolute;bottom:4px;left:4px;
                                                 background:rgba(0,0,0,0.7);color:white;
                                                 padding:2px 6px;border-radius:4px;
                                                 font-size:10px;font-weight:600">
                                        {{ ucfirst($image->type) }}
                                    </span>
                                    {{-- Delete checkbox --}}
                                    <label class="delete-image-label">
                                        <input type="checkbox"
                                               name="delete_images[]"
                                               value="{{ $image->id }}"
                                               class="delete-image-check"
                                               onchange="toggleDeleteOverlay(this)">
                                        <div class="delete-overlay">
                                            <i class="fas fa-trash fa-lg"></i>
                                            <span>Delete</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Add New Images --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:13px">
                            <i class="fas fa-plus me-1 text-primary"></i>
                            Add More Photos
                        </label>
                        <div class="photo-upload-area"
                             onclick="document.getElementById('newImages').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0" style="font-size:14px">
                                Click to add more photos
                            </p>
                        </div>
                        <input type="file" id="newImages" name="images[]"
                               multiple accept="image/*" class="d-none"
                               onchange="previewNewImages(this)">
                        <div id="newImagePreview" class="row g-3 mt-2"></div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-fixgo"
                                style="width:auto;padding:11px 30px">
                           <span style="color: WHITE" ><i class="fas fa-save me-2"></i> Save Changes</span>
                        </button>
                        <a href="{{ route('mechanic.portfolio.view', $portfolio->id) }}"
                           class="btn btn-outline-secondary"
                           style="padding:11px 24px">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.existing-image-card {
    border-radius: 8px;
    overflow: hidden;
}
.delete-image-label {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    cursor: pointer;
}
.delete-image-label input { display: none; }
.delete-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(239,68,68,0);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    font-weight: 700;
    gap: 4px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.delete-overlay.active {
    background: rgba(239,68,68,0.85);
}
</style>
@endpush

@push('scripts')
<script>
function toggleDeleteOverlay(checkbox) {
    const overlay = checkbox.parentElement.querySelector('.delete-overlay');
    overlay.classList.toggle('active', checkbox.checked);
}

function previewNewImages(input) {
    const container = document.getElementById('newImagePreview');
    container.innerHTML = '';
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            container.innerHTML += `
                <div class="col-4 col-md-3">
                    <div class="fixgo-card p-0 overflow-hidden">
                        <img src="${e.target.result}"
                             class="w-100"
                             style="height:90px;object-fit:cover">
                        <div class="p-1">
                            <select name="image_types[]"
                                    class="form-select form-control"
                                    style="font-size:11px;padding:4px 6px">
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