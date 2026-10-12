@extends('layouts.app')

@section('title', 'Create Account - Chamunda Electrics')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.svg') }}" alt="Chamunda Electrics" height="48" class="mb-3">
            <h4 class="fw-bold">Create Customer Account</h4>
            <p class="text-muted small">Register for faster checkout, order history, and exclusive contractor discounts</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('phone') }}">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    <small class="text-muted" style="font-size: 0.75rem;">Minimum 8 characters</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Delivery Address</label>
                    <input type="text" name="address" class="form-control" placeholder="House/Shop no, Area, Landmark" value="{{ old('address') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">City</label>
                    <input type="text" name="city" class="form-control" placeholder="Ahmedabad" value="{{ old('city', 'Ahmedabad') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">PIN Code</label>
                    <input type="text" name="pincode" class="form-control" placeholder="380001" value="{{ old('pincode') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-ce-primary btn-lg w-100 fw-bold shadow-sm mt-4">
                Register Account <i class="bi bi-person-check-fill ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top small">
            Already have an account? <a href="{{ route('login') }}" class="fw-bold text-ce-primary text-decoration-none">Sign In</a>
        </div>
    </div>
</div>
@endsection
