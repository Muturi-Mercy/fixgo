@extends('layouts.app')

@section('title', 'Request Assistance')
@section('page-title', 'Request Assistance')

@section('sidebar-menu')
    <a href="{{ route('user.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('user.request-assistance') }}" class="nav-link active">
        <i class="fas fa-plus-circle"></i> Request Assistance
    </a>
    <a href="{{ route('user.my-requests') }}" class="nav-link">
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

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Page Header --}}
        <div class="mb-4">
            <h4 style="color:#1a3c6e; font-weight:750; margin:0;>
                {{-- <i class="fas fa-tools me-2 text-warning"></i> Request Roadside Assistance --}}
            </h4>
            <p class="text-muted mb-0" style="font-size:14px">
                Fill in the details below and we'll find a mechanic near you.
            </p>
        </div>

        {{-- Step Indicator --}}
        <div class="step-indicator mb-4">
            <div class="step-item active" id="step-indicator-1">
                <div class="step-circle">1</div>
                <span>Service</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item" id="step-indicator-2">
                <div class="step-circle">2</div>
                <span>Location</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item" id="step-indicator-3">
                <div class="step-circle">3</div>
                <span>Details</span>
            </div>
            <div class="step-line"></div>
            <div class="step-item" id="step-indicator-4">
                <div class="step-circle">4</div>
                <span>Confirm</span>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('user.store-request') }}"
              enctype="multipart/form-data" id="requestForm">
            @csrf

            {{-- STEP 1: Service --}}
            <div class="step-content" id="step-1">
                <div class="fixgo-card">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-wrench me-2 text-primary"></i>Step 1: Select Service</h6>
                    </div>
                    <div class="fixgo-card-body">

                        {{-- Vehicle Type --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-car me-1 text-primary"></i> Vehicle Type
                            </label>
                            <select name="vehicle_category_id" class="form-select form-control" required>
                                <option value="">-- Select your vehicle type --</option>
                                @foreach($vehicleCategories as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service Required --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tools me-1 text-primary"></i> Service Required
                            </label>
                            <div class="row g-3">
                                @foreach($serviceCategories as $service)
                                <div class="col-6 col-md-4">
                                    <label class="service-option">
                                        <input type="radio" name="service_category_id"
                                               value="{{ $service->id }}" required>
                                        <div class="service-option-card">
                                            <i class="{{ getServiceIcon($service->name) }}"></i>
                                            <span>{{ $service->name }}</span>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-fixgo"
                                    style="width:auto;padding:10px 30px"
                                    onclick="nextStep(1)">
                               <span style="color: white" > Next<i class="fas fa-arrow-right ms-2"></i></span> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Location --}}
            <div class="step-content d-none" id="step-2">
                <div class="fixgo-card">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-map-marker-alt me-2 text-primary"></i>Step 2: Your Location</h6>
                    </div>
                    <div class="fixgo-card-body">

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary w-100 mb-3"
                                    onclick="getLocation()">
                                <i class="fas fa-crosshairs me-2"></i> Use My Current Location
                            </button>
                            <div id="locationStatus" class="text-muted text-center"
                                 style="font-size:13px"></div>
                        </div>

                        {{-- Map --}}
                        <div id="locationMap" style="height:300px;border-radius:12px;
                             border:2px solid #e5e7eb;margin-bottom:16px"></div>

                        {{-- Address --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-map-pin me-1 text-primary"></i> Address / Landmark
                            </label>
                            <input type="text" name="user_address" id="userAddress"
                                   class="form-control" placeholder="e.g. Ngong Road, Nairobi">
                        </div>

                        <input type="hidden" name="user_latitude" id="userLat">
                        <input type="hidden" name="user_longitude" id="userLng">

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary"
                                    style="padding:10px 25px" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </button>
                            <button type="button" class="btn btn-fixgo"
                                    style="width:auto;padding:10px 30px" onclick="nextStep(2)">
                               <span style="color: white" > Next<i class="fas fa-arrow-right ms-2"></i></span> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3: Details --}}
            <div class="step-content d-none" id="step-3">
                <div class="fixgo-card">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-info-circle me-2 text-primary"></i>Step 3: Problem Details</h6>
                    </div>
                    <div class="fixgo-card-body">

                        {{-- Problem Description --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment-alt me-1 text-primary"></i>
                                Describe the Problem
                            </label>
                            <textarea name="problem_description" class="form-control"
                                      rows="4" required
                                      placeholder="e.g. Car won't start. Battery seems to be dead."></textarea>
                        </div>

                        {{-- Photo Upload --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-camera me-1 text-primary"></i>
                                Upload Photos (Optional)
                            </label>
                            <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0" style="font-size:14px">
                                    Click to upload photos of your vehicle/problem
                                </p>
                                <p class="text-muted" style="font-size:12px">
                                    PNG, JPG up to 5MB each
                                </p>
                            </div>
                            <input type="file" id="photoInput" name="photos[]"
                                   multiple accept="image/*" class="d-none"
                                   onchange="previewPhotos(this)">
                            <div id="photoPreview" class="d-flex gap-2 flex-wrap mt-3"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary"
                                    style="padding:10px 25px" onclick="prevStep(3)">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </button>
                            <button type="button" class="btn btn-fixgo"
                                    style="width:auto;padding:10px 30px" onclick="nextStep(3)">
                                <span style="color: white" > Next<i class="fas fa-arrow-right ms-2"></i></span> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 4: Confirm --}}
            <div class="step-content d-none" id="step-4">
                <div class="fixgo-card">
                    <div class="fixgo-card-header">
                        <h6><i class="fas fa-check-circle me-2 text-primary"></i>Step 4: Confirm Request</h6>
                    </div>
                    <div class="fixgo-card-body">

                        <div class="confirm-box mb-3">
                            <div class="confirm-row">
                                <span class="confirm-label">
                                    <i class="fas fa-car me-2 text-primary"></i> Vehicle
                                </span>
                                <span class="confirm-value" id="confirm-vehicle">—</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label">
                                    <i class="fas fa-tools me-2 text-primary"></i> Service
                                </span>
                                <span class="confirm-value" id="confirm-service">—</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i> Location
                                </span>
                                <span class="confirm-value" id="confirm-location">—</span>
                            </div>
                            <div class="confirm-row">
                                <span class="confirm-label">
                                    <i class="fas fa-comment-alt me-2 text-primary"></i> Problem
                                </span>
                                <span class="confirm-value" id="confirm-problem">—</span>
                            </div>
                        </div>

                        <div class="alert alert-info" style="border-radius:10px;font-size:13px">
                            <i class="fas fa-info-circle me-2"></i>
                            Once submitted, nearby available mechanics will be notified
                            and can accept your request.
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary"
                                    style="padding:10px 25px" onclick="prevStep(4)">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </button>
                            <button type="submit" class="btn btn-fixgo"
                                    style="width:auto;padding:10px 30px">
                                    <span style="color: white" > <i class="fas fa-paper-plane me-2"></i> Submit Request</span> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.step-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
}
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.step-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.3s ease;
}
.step-item.active .step-circle {
    background: linear-gradient(135deg, #1a3c6e, #3b82f6);
    color: white;
}
.step-item.completed .step-circle {
    background: #10b981;
    color: white;
}
.step-item span {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
}
.step-item.active span { color: #1a3c6e; }
.step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 8px;
    margin-bottom: 20px;
}
.service-option input { display: none; }
.service-option-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9fafb;
}
.service-option-card:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}
.service-option input:checked + .service-option-card {
    border-color: #1a3c6e;
    background: #eff6ff;
}
.service-option-card i {
    font-size: 24px;
    color: #3b82f6;
    display: block;
    margin-bottom: 8px;
}
.service-option-card span {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
}
.photo-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.photo-upload-area:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}
.photo-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
}
.confirm-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}
.confirm-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #e5e7eb;
    gap: 10px;
}
.confirm-row:last-child { border-bottom: none; }
.confirm-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
    min-width: 100px;
}
.confirm-value {
    font-size: 13px;
    color: #1a3c6e;
    font-weight: 600;
    text-align: right;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map, marker;
let currentStep = 1;

// Init map on step 2
function initMap(lat, lng) {
    if (map) map.remove();
    map = L.map('locationMap').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        document.getElementById('userLat').value = pos.lat;
        document.getElementById('userLng').value = pos.lng;
        reverseGeocode(pos.lat, pos.lng);
    });
    document.getElementById('userLat').value = lat;
    document.getElementById('userLng').value = lng;
}

function getLocation() {
    const status = document.getElementById('locationStatus');
    status.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting your location...';
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                initMap(lat, lng);
                reverseGeocode(lat, lng);
                status.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>Location found!';
            },
            function() {
                status.innerHTML = '<i class="fas fa-exclamation-circle text-danger me-2"></i>Could not get location. Please enter manually.';
                initMap(-1.2921, 36.8219); // Default: Nairobi
            }
        );
    }
}

function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
        .then(r => r.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('userAddress').value = data.display_name;
            }
        });
}

function nextStep(step) {
    // Validation
    if (step === 1) {
        const vehicle = document.querySelector('select[name="vehicle_category_id"]').value;
        const service = document.querySelector('input[name="service_category_id"]:checked');
        if (!vehicle) { alert('Please select a vehicle type.'); return; }
        if (!service) { alert('Please select a service.'); return; }
    }
    if (step === 2) {
        const lat = document.getElementById('userLat').value;
        if (!lat) { alert('Please share your location first.'); return; }
    }
    if (step === 3) {
        const desc = document.querySelector('textarea[name="problem_description"]').value;
        if (!desc.trim()) { alert('Please describe the problem.'); return; }
        populateConfirm();
    }

    document.getElementById('step-' + step).classList.add('d-none');
    document.getElementById('step-' + (step + 1)).classList.remove('d-none');

    // Update indicators
    document.getElementById('step-indicator-' + step).classList.remove('active');
    document.getElementById('step-indicator-' + step).classList.add('completed');
    document.getElementById('step-indicator-' + (step + 1)).classList.add('active');

    currentStep = step + 1;

    // Init map when entering step 2
    if (step + 1 === 2) {
        setTimeout(() => initMap(-1.2921, 36.8219), 100);
    }
}

function prevStep(step) {
    document.getElementById('step-' + step).classList.add('d-none');
    document.getElementById('step-' + (step - 1)).classList.remove('d-none');
    document.getElementById('step-indicator-' + step).classList.remove('active');
    document.getElementById('step-indicator-' + (step - 1)).classList.remove('completed');
    document.getElementById('step-indicator-' + (step - 1)).classList.add('active');
    currentStep = step - 1;
}

function populateConfirm() {
    const vehicleSelect = document.querySelector('select[name="vehicle_category_id"]');
    const serviceInput = document.querySelector('input[name="service_category_id"]:checked');
    const address = document.getElementById('userAddress').value;
    const problem = document.querySelector('textarea[name="problem_description"]').value;

    document.getElementById('confirm-vehicle').textContent =
        vehicleSelect.options[vehicleSelect.selectedIndex].text;
    document.getElementById('confirm-service').textContent =
        serviceInput ? serviceInput.closest('.service-option').querySelector('span').textContent : '—';
    document.getElementById('confirm-location').textContent = address || 'Location set on map';
    document.getElementById('confirm-problem').textContent = problem;
}

function previewPhotos(input) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'photo-thumb';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush