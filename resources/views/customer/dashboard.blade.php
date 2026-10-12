@extends('layouts.app')

@section('title', 'My Account - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Account</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="text-center p-3 border-bottom mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-4 mb-2 shadow" style="width: 56px; height: 56px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>

                <div class="nav flex-column nav-pills gap-1">
                    <a href="{{ route('customer.dashboard') }}" class="nav-link active fw-semibold"><i class="bi bi-grid me-2"></i> Dashboard</a>
                    <a href="{{ route('customer.orders') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-box-seam me-2"></i> My Orders</a>
                    <a href="{{ route('customer.wishlist') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-heart me-2"></i> Wishlist</a>
                    <a href="{{ route('customer.profile') }}" class="nav-link text-dark fw-semibold"><i class="bi bi-gear me-2"></i> Profile Settings</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 pt-2 border-top">
                        @csrf
                        <button type="submit" class="nav-link text-danger w-100 text-start fw-semibold"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-9">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary p-3 rounded-3">
                                <i class="bi bi-box-seam fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger-subtle text-danger p-3 rounded-3">
                                <i class="bi bi-heart fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $wishlistCount }}</h4>
                                <small class="text-muted">Saved in Wishlist</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded-3">
                                <i class="bi bi-patch-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Verified Customer</h6>
                                <small class="text-muted">Active Member</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('customer.orders') }}" class="small fw-bold text-decoration-none text-ce-primary">View All</a>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $ord)
                                    <tr>
                                        <td class="fw-bold text-ce-primary">{{ $ord->order_number }}</td>
                                        <td>{{ $ord->created_at->format('d M Y') }}</td>
                                        <td>{{ $ord->items->count() }} Products</td>
                                        <td class="fw-bold">₹{{ number_format($ord->total_amount, 2) }}</td>
                                        <td><span class="badge {{ $ord->status_badge_class }}">{{ ucfirst($ord->order_status) }}</span></td>
                                        <td>
                                            <a href="{{ route('customer.orders.show', $ord->order_number) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-box text-secondary display-5 mb-2"></i>
                        <p>You haven't placed any orders yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-ce-primary btn-sm">Explore Shop</a>
                    </div>
                @endif
            </div>

            <!-- Saved Delivery Address -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Default Delivery Address</h5>
                    <a href="{{ route('customer.profile') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
                <p class="text-secondary mb-0">
                    {{ $user->address ?? 'No address saved yet.' }}<br>
                    {{ $user->city ?? '' }}{{ $user->state ? ', ' . $user->state : '' }} {{ $user->pincode ? '- ' . $user->pincode : '' }}<br>
                    <small>Phone: {{ $user->phone ?? 'Not provided' }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
