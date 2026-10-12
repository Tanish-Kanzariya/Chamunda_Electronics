@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}" class="text-decoration-none">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-4">
            <div>
                <h4 class="fw-bold mb-1">Order #{{ $order->order_number }}</h4>
                <small class="text-muted">Placed on {{ $order->created_at->format('d F Y, h:i A') }}</small>
            </div>
            <div class="d-flex gap-2 mt-2 mt-sm-0">
                <a href="{{ route('checkout.invoice', $order->order_number) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i> Print Invoice
                </a>
            </div>
        </div>

        <!-- Tracking Visualizer -->
        @php
            $statuses = ['pending' => 'Order Received', 'processing' => 'Processing', 'shipped' => 'Dispatched', 'delivered' => 'Delivered'];
            $currentStatus = $order->order_status;
            $orderKeys = array_keys($statuses);
            $currentIndex = array_search($currentStatus, $orderKeys);
        @endphp

        @if($currentStatus !== 'cancelled')
            <div class="bg-light p-4 rounded-4 mb-4">
                <h6 class="fw-bold text-secondary text-uppercase small mb-3">Order Status Progression</h6>
                <div class="row g-2 text-center">
                    @foreach($statuses as $stKey => $stLabel)
                        @php $stIndex = array_search($stKey, $orderKeys); @endphp
                        <div class="col-3 position-relative">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2 {{ $stIndex <= $currentIndex ? 'bg-success text-white shadow-sm' : 'bg-secondary-subtle text-secondary' }}" style="width: 36px; height: 36px;">
                                @if($stIndex < $currentIndex)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    {{ $stIndex + 1 }}
                                @endif
                            </div>
                            <div class="small fw-semibold {{ $stIndex <= $currentIndex ? 'text-success' : 'text-muted' }}">{{ $stLabel }}</div>
                        </div>
                    @endforeach
                </div>
                @if($order->tracking_number)
                    <div class="mt-3 text-center small text-secondary">
                        <i class="bi bi-truck me-1"></i> Tracking AWB: <strong class="text-dark">{{ $order->tracking_number }}</strong>
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-danger mb-4">
                <i class="bi bi-x-circle-fill me-1"></i> This order was cancelled.
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 h-100 bg-white">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2">Delivery Address</h6>
                    <div class="fw-bold">{{ $order->customer_name }}</div>
                    <div class="text-secondary small">{{ $order->shipping_address }}</div>
                    <div class="text-secondary small">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
                    <div class="text-secondary small mt-1"><i class="bi bi-telephone me-1"></i> Phone: {{ $order->customer_phone }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 h-100 bg-white">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-2">Payment Details</h6>
                    <div class="small mb-1">Method: <strong class="text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</strong></div>
                    <div class="small mb-1">Payment Status: <span class="badge {{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span></div>
                    @if($order->notes)
                        <div class="small mt-2 pt-2 border-top text-muted"><strong>Instructions:</strong> {{ $order->notes }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <h6 class="fw-bold text-secondary text-uppercase small mb-3">Items in this Order</h6>
        <div class="table-responsive border rounded-3 mb-4">
            <table class="table align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <span class="fw-bold text-dark">{{ $item->product_name }}</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $item->product_sku ?? 'N/A' }}</small></td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light small">
                    <tr>
                        <td colspan="4" class="text-end text-secondary">Subtotal:</td>
                        <td class="text-end fw-semibold">₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="4" class="text-end text-success">Coupon Discount ({{ $order->coupon_code }}):</td>
                            <td class="text-end text-success fw-bold">- ₹{{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="text-end text-secondary">GST (18%):</td>
                        <td class="text-end fw-semibold">₹{{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end text-secondary">Shipping:</td>
                        <td class="text-end fw-semibold">{{ $order->shipping_charge == 0 ? 'FREE' : '₹' . number_format($order->shipping_charge, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold fs-6">Grand Total:</td>
                        <td class="text-end fw-bold fs-6 text-ce-primary">₹{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div>
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to All Orders
            </a>
        </div>
    </div>
</div>
@endsection
