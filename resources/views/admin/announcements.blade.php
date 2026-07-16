@extends('layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')

{{-- <div class="mb-4">
    <h4 style="color:#1a3c6e;font-weight:700;margin:0">
        <i class="fas fa-bullhorn me-2 text-primary"></i> Announcements
    </h4>
    <p class="text-muted mb-0" style="font-size:14px">
        Send announcements to users, mechanics, or everyone.
    </p>
</div> --}}

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row g-4">

    {{-- Create Announcement --}}
    <div class="col-lg-5">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    New Announcement
                </h6>
            </div>
            <div class="fixgo-card-body">
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Title
                        </label>
                        <input type="text" name="title" class="form-control"
                               placeholder="Announcement title" required
                               value="{{ old('title') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Message
                        </label>
                        <textarea name="message" class="form-control" rows="4"
                                  placeholder="Write your announcement..."
                                  required>{{ old('message') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:13px">
                            Send To
                        </label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="service-option w-100">
                                    <input type="radio" name="target"
                                           value="all" checked>
                                    <div class="service-option-card">
                                        <i class="fas fa-users"></i>
                                        <span>Everyone</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="service-option w-100">
                                    <input type="radio" name="target" value="users">
                                    <div class="service-option-card">
                                        <i class="fas fa-car"></i>
                                        <span>Users Only</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="service-option w-100">
                                    <input type="radio" name="target" value="mechanics">
                                    <div class="service-option-card">
                                        <i class="fas fa-tools"></i>
                                        <span>Mechanics</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-fixgo w-100"
                            style="padding:12px">
                       <span style="color:#f97316 "> <i class="fas fa-paper-plane me-2"></i> Send Announcement</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Announcements List --}}
    <div class="col-lg-7">
        <div class="fixgo-card">
            <div class="fixgo-card-header">
                <h6>
                    <i class="fas fa-history me-2 text-primary"></i>
                    Sent Announcements
                </h6>
            </div>
            <div class="fixgo-card-body p-0">
                @if($announcements->count())
                    @foreach($announcements as $ann)
                    <div class="notification-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notification-icon unread-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="flex-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <h6 style="font-weight:700;color:#1a3c6e;
                                               margin:0;font-size:14px">
                                        {{ $ann->title }}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size:11px;color:#9ca3af">
                                            {{ $ann->created_at->diffForHumans() }}
                                        </span>
                                        <form method="POST"
                                              action="{{ route('admin.announcements.delete', $ann->id) }}"
                                              onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    style="padding:2px 8px;font-size:11px">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p style="font-size:13px;color:#6b7280;
                                          margin-bottom:6px;line-height:1.5">
                                    {{ $ann->message }}
                                </p>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="background:{{ $ann->target === 'all' ? '#eff6ff' :
                                                                ($ann->target === 'users' ? '#f0fdf4' : '#fff7ed') }};
                                                 color:{{ $ann->target === 'all' ? '#1a3c6e' :
                                                          ($ann->target === 'users' ? '#065f46' : '#c2410c') }};
                                                 padding:3px 10px;border-radius:10px;
                                                 font-size:11px;font-weight:700">
                                        <i class="fas fa-{{ $ann->target === 'all' ? 'users' :
                                                            ($ann->target === 'users' ? 'car' : 'tools') }} me-1"></i>
                                        {{ ucfirst($ann->target) }}
                                    </span>
                                    <span style="font-size:11px;color:#9ca3af">
                                        by {{ $ann->admin->name ?? 'Admin' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="p-3 d-flex justify-content-center">
                        {{ $announcements->links() }}
                    </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-bullhorn fa-4x text-muted mb-4"></i>
                    <h5 style="color:#1a3c6e;font-weight:700">No Announcements</h5>
                    <p class="text-muted">Create your first announcement.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection