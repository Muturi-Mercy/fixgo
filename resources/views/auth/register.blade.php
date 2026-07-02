@extends('layouts.guest')

@section('title', 'Register')

@section('content')

<div class="auth-card" style="max-width: 500px; padding:0px">
    {{-- Header --}}
    <div class="auth-header"style="padding:10px;">
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <img src="{{ asset('images/driverlog.png') }}" alt="Driver Logo"  style="width:65px; height:65px; object-fit:contain;">
            </div>
            <div>
                <h1>FixGo</h1>
                <p>Fix Smart. Go Safe.</p>
            </div>
        </div>
        <h2>Create your account</h2>
    </div>

    {{-- Body --}}
    <div class="auth-body"style="padding-top:10px;">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Role Selector --}}
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-user-tag me-1 text-primary"></i> Register as
                </label>

                <div class="role-selector" style="padding: 0px">
                    <button type="button" class="role-card selected"id="role-user" onclick="selectRole('user')">
                        <i class="fas fa-car"></i>
                        <span>Driver</span>
                    </button>

                    <button type="button" class="role-card" id="role-mechanic" onclick="selectRole('mechanic')">
                        <i class="fas fa-tools"></i>
                        <span>Mechanic</span>
                    </button>
                </div>

                <input type="hidden" name="role" id="roleInput" value="user">
            </div>

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-user me-1 text-primary"></i> Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                    autofocus
                >

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-envelope me-1 text-primary"></i> Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                >

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-phone me-1 text-primary"></i> Phone Number
                </label>

                <input
                    type="text"
                    name="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}"
                    placeholder="e.g. 0712345678"
                    required
                >

                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-lock me-1 text-primary"></i> Password
                </label>

                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="passwordField"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Create a password"
                        required
                    >

                    <span class="input-group-text" style="cursor:pointer"
                        onclick="togglePassword('passwordField','eyeIcon1')">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </span>
                </div>

                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-lock me-1 text-primary"></i> Confirm Password
                </label>

                <div class="input-group">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="passwordField2"
                        class="form-control"
                        placeholder="Confirm your password"
                        required
                    >

                    <span class="input-group-text" style="cursor:pointer"
                        onclick="togglePassword('passwordField2','eyeIcon2')">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </span>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-fixgo">
                <i class="fas fa-user-plus me-2" style="color: #fff;"></i>
                <span id="registerBtnText" style="color: #fff;">Create Account</span>
            </button>

        </form>
    </div>

    {{-- Footer --}}
    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}">Sign in here</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;

    document.getElementById('role-user').classList.remove('selected');
    document.getElementById('role-mechanic').classList.remove('selected');

    document.getElementById('role-' + role).classList.add('selected');

    document.getElementById('registerBtnText').textContent =
        role === 'mechanic'
            ? 'Create Account'
            : 'Create Account';
}

function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush