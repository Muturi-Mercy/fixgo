@extends('layouts.app')

@section('title', 'My Jobs')
@section('page-title', 'My Jobs')

@section('sidebar-menu')
    <a href="{{ route('mechanic.dashboard') }}" class="nav-link">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('mechanic.service-requests') }}" class="nav-link">
        <i class="fas fa-bell"></i> Service Requests
    </a>
    <a href="{{ route('mechanic.my-jobs') }}" class="nav-link active">
        <i class="fas fa-briefcase"></i> My Jobs
    </a>
    <a href="{{ route('mechanic.earnings') }}" class="nav-link">
        <i class="fas fa-wallet"></i> Earnings
    </a>
    <a href="{{ route('mechanic.portfolio') }}" class="nav-link">
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="color:#1a3c6e;font-weight:700;margin:0">
            {{--<i class="fas fa-briefcase me-2 text-primary"></i> My Jobs--}}
        </h4>
        <p class="text-muted mb-0" style="color:#1a3c6e;font-weight:700;margin:0">
            All your assigned service jobs.
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"
         style="border-radius:12px;border:none;background:#d1fae5;color:#065f46">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Filter Tabs --}}
<div class="fixgo-card mb-4">
    <div class="fixgo-card-body" style="padding:12px 20px">
        <div class="d-flex gap-2 flex-wrap">
            <span style="padding-right: 80px">
                <a href="{{ route('mechanic.my-jobs') }}"
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list me-1"></i> All
                </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('mechanic.my-jobs', ['status'=>'accepted']) }}"
               class="filter-tab {{ request('status')=='accepted' ? 'active' : '' }}">
                <i class="fas fa-check me-1"></i> Accepted
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('mechanic.my-jobs', ['status'=>'on_the_way']) }}"
               class="filter-tab {{ request('status')=='on_the_way' ? 'active' : '' }}">
                <i class="fas fa-car me-1"></i> On The Way
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('mechanic.my-jobs', ['status'=>'repairing']) }}"
               class="filter-tab {{ request('status')=='repairing' ? 'active' : '' }}">
                <i class="fas fa-wrench me-1"></i> Repairing
            </a>
            </span>

            <span style="padding-right: 80px">
            <a href="{{ route('mechanic.my-jobs', ['status'=>'completed']) }}"
               class="filter-tab {{ request('status')=='completed' ? 'active' : '' }}">
                <i class="fas fa-flag-checkered me-1"></i> Completed
            </a>
            </span>

        </div>
    </div>
</div>

{{-- Jobs Table --}}
<div class="fixgo-card">
    <div class="fixgo-card-body p-0">
        @if($jobs->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc">
                    <tr>
                        <th style="font-size:12px;color:#6b7280;
                                   font-weight:600;padding:12px 20px">JOB</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">CUSTOMER</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">VEHICLE</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">STATUS</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">DATE</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">AMOUNT</th>
                        <th style="font-size:12px;color:#6b7280;font-weight:600">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td style="padding:14px 20px">
                            <div style="font-weight:600;font-size:14px">
                                {{ $job->serviceCategory->name ?? 'N/A' }}
                            </div>
                            <div style="font-size:12px;color:#6b7280">
                                {{ $job->request_number }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px;font-weight:600">
                                {{ $job->user->name }}
                            </div>
                            <div style="font-size:12px;color:#6b7280">
                                {{ $job->user->phone ?? '' }}
                            </div>
                        </td>
                        <td style="font-size:13px">
                            {{ $job->vehicleCategory->name ?? 'N/A' }}
                        </td>
                        <td>
                            <span class="status-badge
                                  badge-{{ str_replace('_','-',$job->status) }}">
                                {{ ucwords(str_replace('_',' ',$job->status)) }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:#6b7280">
                            {{ $job->created_at->format('M d, Y') }}
                        </td>
                        <td style="font-weight:600;color:#1a3c6e">
                            {{ $job->price ? 'KSh '.number_format($job->price) : '—' }}
                        </td>
                        <td>
                            @if(!in_array($job->status, ['completed','cancelled']))
                            <form method="POST"
                                  action="{{ route('mechanic.update-request-status', $job->id) }}"
                                  class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="status"
                                        class="form-select"
                                        style="font-size:12px;padding:5px 8px;width:130px">
                                    <option value="accepted"
                                        {{ $job->status==='accepted'?'selected':'' }}>
                                        Accepted
                                    </option>
                                    <option value="on_the_way"
                                        {{ $job->status==='on_the_way'?'selected':'' }}>
                                        On The Way
                                    </option>
                                    <option value="arrived"
                                        {{ $job->status==='arrived'?'selected':'' }}>
                                        Arrived
                                    </option>
                                    <option value="repairing"
                                        {{ $job->status==='repairing'?'selected':'' }}>
                                        Repairing
                                    </option>
                                    <option value="completed"
                                        {{ $job->status==='completed'?'selected':'' }}>
                                        Completed
                                    </option>
                                </select>
                                <button type="submit"
                                        class="btn btn-sm btn-fixgo"
                                        style="width:auto;padding:5px 10px">
                                   <span style="color: white"> <i class="fas fa-save"></i></span>
                                </button>
                            </form>
                            @else
                                <span class="text-muted" style="font-size:13px">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-3 d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                <h5 style="color:#1a3c6e;font-weight:700">No Jobs Found</h5>
                <p class="text-muted">
                    {{ request('status') ? 'No '.request('status').' jobs found.' : 'No jobs assigned yet.' }}
                </p>
            </div>
        @endif
    </div>
</div>

@endsection