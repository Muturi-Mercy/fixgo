@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Manage Categories')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

<div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        {{--<i class="fas fa-tags me-2 text-primary"></i> Manage Categories--}}
    </h4>
    <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
        {{-- Manage service and vehicle categories. --}}
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Service Categories --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-tools me-2 text-primary"></i>Service Categories</h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $serviceCategories->count() }} categories
                </span>
            </div>
            <div class="fixgo-card-body">

                {{-- Add Form --}}
                <form method="POST"
                      action="{{ route('admin.categories.service.store') }}"
                      class="mb-4">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. AC Repair"
                               style="font-size:13px">
                        <button type="submit" class="btn btn-fixgo"
                                style="width:auto;padding:10px 20px;white-space:nowrap">
                           <span style="color: white"> <i class="fas fa-plus me-1"></i> Add</span>
                        </button>
                    </div>
                    @error('name')
                        <div class="text-danger mt-1" style="font-size:12px">
                            {{ $message }}
                        </div>
                    @enderror
                </form>

                {{-- Categories List --}}
                <div style="max-height:400px;overflow-y:auto">
                    @foreach($serviceCategories as $cat)
                    <div class="d-flex align-items-center justify-content-between
                                p-3 mb-2"
                         style="background:#f8fafc;border-radius:10px;
                                border:1px solid #f0f4ff">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon blue"
                                 style="width:36px;height:36px;font-size:14px;flex-shrink:0">
                                <i class="{{ getServiceIcon($cat->name) }}"></i>
                            </div>
                            <div>
                                <p style="font-weight:600;color:#1a3c6e;
                                           margin:0;font-size:14px">
                                    {{ $cat->name }}
                                </p>
                                <p style="font-size:11px;color:#6b7280;margin:0">
                                    {{ $cat->breakdown_requests_count ?? 0 }} requests
                                </p>
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ route('admin.categories.service.delete', $cat->id) }}"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    style="padding:5px 10px">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Vehicle Categories --}}
    <div class="col-lg-6">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6><i class="fas fa-car me-2 text-primary"></i>Vehicle Categories</h6>
                <span class="text-muted" style="font-size:13px">
                    {{ $vehicleCategories->count() }} categories
                </span>
            </div>
            <div class="fixgo-card-body">

                {{-- Add Form --}}
                <form method="POST"
                      action="{{ route('admin.categories.vehicle.store') }}"
                      class="mb-4">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. Electric Vehicle"
                               style="font-size:13px">
                        <button type="submit" class="btn btn-fixgo"
                                style="width:auto;padding:10px 20px;white-space:nowrap">
                            <span style="color: white"><i class="fas fa-plus me-1"></i> Add</span>
                        </button>
                    </div>
                </form>

                {{-- Categories List --}}
                <div style="max-height:400px;overflow-y:auto">
                    @foreach($vehicleCategories as $cat)
                    <div class="d-flex align-items-center justify-content-between
                                p-3 mb-2"
                         style="background:#f8fafc;border-radius:10px;
                                border:1px solid #f0f4ff">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-icon orange"
                                 style="width:36px;height:36px;font-size:14px;flex-shrink:0">
                                <i class="fas fa-car"></i>
                            </div>
                            <div>
                                <p style="font-weight:600;color:#1a3c6e;
                                           margin:0;font-size:14px">
                                    {{ $cat->name }}
                                </p>
                                <p style="font-size:11px;color:#6b7280;margin:0">
                                    {{ $cat->breakdown_requests_count ?? 0 }} requests
                                </p>
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ route('admin.categories.vehicle.delete', $cat->id) }}"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    style="padding:5px 10px">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection