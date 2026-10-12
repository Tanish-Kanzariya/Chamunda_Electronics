@extends('layouts.app')

@section('title', 'My Orders - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Orders</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h4 class="fw-bold mb-0"><i class="bi bi-box-seam text-ce-primary me-2"></i> Order History</h4>
            <a href="{{ route('shop.index') }}" class="btn btn-ce-primary btn-sm">New Order</a>
        </div>

        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>Order Number</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $ord)
                            <tr>
                                <td>
                                    <span class="fw-bold text-ce-primary">{{ $ord->order_number }}</span>
                                    <small class="d-block text-muted">{{ $ord->items->count() }} Items</small>
                                </td>
                                <td>{{ $ord->created_at->format('d M Y, h:i A') }}</td>
                                <td class="fw-bold">₹{{ number_format($ord->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $ord->payment_badge_class }}">{{ ucfirst($ord->payment_status) }}</span>
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $ord->payment_method) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $ord->status_badge_class }}">{{ ucfirst($ord->order_status) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('customer.orders.show', $ord->order_number) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        <a href="{{ route('checkout.invoice', $ord->order_number) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Invoice">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box-seam display-2 text-secondary mb-3"></i>
                <h5 class="fw-bold">No orders placed yet</h5>
                <p>Browse our electrical catalog and get your supplies delivered right to your doorstep.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-ce-primary px-4 fw-bold">Shop Now</a>
            </div>
        @endif
    </div>
</div>
@endsection
