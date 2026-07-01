@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<div class="auth-card">
    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div>
                <h1>FixGo</h1>
                <p>We fix it. You go.</p>
            </div>
        </div>
        <h2>Welcome back! Please sign in.</h2>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                    autofocus
                >

                @error('email')
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
                        placeholder="Enter your password"
                        required
                    >

                    <span class="input-group-text" style="cursor:pointer" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>

                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                    >

                    <label class="form-check-label text-muted" for="remember" style="font-size:13px">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        style="font-size:13px; color:#3b82f6; text-decoration:none; font-weight:600"
                    >
                        Forgot password?
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-fixgo">
                <i class="fas fa-sign-in-alt me-2"></i>
                Sign In
            </button>

        </form>
    </div>

    {{-- Footer --}}
    <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}">Create one now</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePassword() {
    const field = document.getElementById('passwordField');
    const icon = document.getElementById('eyeIcon');

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