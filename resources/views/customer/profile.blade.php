@extends('layouts.app')

@section('title', 'Profile Settings - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="nav flex-column nav-pills gap-1">
                    <a href="{{ route('customer.dashboard') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-grid me-2"></i> Dashboard</a>
                    <a href="{{ route('customer.orders') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-box-seam me-2"></i> My Orders</a>
                    <a href="{{ route('customer.wishlist') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-heart me-2"></i> Wishlist</a>
                    <a href="{{ route('customer.profile') }}" class="nav-link active fw-semibold"><i class="bi bi-gear me-2"></i> Profile Settings</a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold mb-4 pb-2 border-bottom">Profile & Delivery Address</h4>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address (Read Only)</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Contact Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Default Delivery Address</label>
                            <textarea name="address" rows="3" class="form-control" placeholder="House/Site Address">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $user->state ?? 'Gujarat') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">PIN Code</label>
                            <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $user->pincode) }}">
                        </div>
                    </div>

                    <h5 class="fw-bold mt-5 mb-3 pt-3 border-top">Change Password</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-ce-primary px-4 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
