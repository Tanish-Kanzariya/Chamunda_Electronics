@extends('layouts.app')

@section('title', 'Sign In - Chamunda Electrics')

@section('content')
<div class="container py-5" style="max-width: 480px;">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.svg') }}" alt="Chamunda Electrics" height="48" class="mb-3">
            <h4 class="fw-bold">Sign In to Your Account</h4>
            <p class="text-muted small">Access your orders, track shipments, and manage your account</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold mb-0">Password</label>
                <input type="password" name="password" id="password" class="form-control mt-1" placeholder="Enter your password" required>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-ce-primary btn-lg w-100 fw-bold shadow-sm">
                Sign In <i class="bi bi-box-arrow-in-right ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top small">
            Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-ce-primary text-decoration-none">Create Account</a>
        </div>
    </div>
</div>
@endsection
