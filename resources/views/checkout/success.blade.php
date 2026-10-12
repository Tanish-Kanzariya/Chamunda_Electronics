@extends('layouts.app')

@section('title', 'Order Confirmation - Chamunda Electrics')

@section('content')
<div class="container py-5 text-center" style="max-width: 800px;">
    <div class="card border-0 shadow-sm rounded-4 p-5 text-start">
        <div class="text-center mb-4">
            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 72px; height: 72px;">
                <i class="bi bi-check-lg display-5"></i>
            </div>
            <h2 class="fw-bold text-dark">Thank You for Your Order!</h2>
            <p class="text-muted">Your electrical goods order has been placed successfully and is being prepared for dispatch.</p>
            <div class="badge bg-light text-dark border fs-6 px-3 py-2 font-monospace">
                Order Number: <strong class="text-ce-primary">{{ $order->order_number }}</strong>
            </div>
        </div>

        <div class="row g-4 mb-4 pt-3 border-top">
            <div class="col-md-6">
                <h6 class="fw-bold text-secondary text-uppercase small">Delivery Details</h6>
                <div class="fw-bold">{{ $order->customer_name }}</div>
                <div class="text-secondary small">{{ $order->shipping_address }}</div>
                <div class="text-secondary small">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
                <div class="text-secondary small mt-1"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone }}</div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-secondary text-uppercase small">Payment & Status</h6>
                <div class="small mb-1">
                    Payment Method: <span class="badge bg-dark text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="small mb-1">
                    Payment Status: <span class="badge {{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="small">
                    Order Status: <span class="badge {{ $order->status_badge_class }}">{{ ucfirst($order->order_status) }}</span>
                </div>
            </div>
        </div>

        <!-- Ordered Items Table -->
        <h6 class="fw-bold text-secondary text-uppercase small mb-2">Order Items</h6>
        <div class="table-responsive border rounded-3 mb-4">
            <table class="table align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <span class="fw-bold d-block">{{ $item->product_name }}</span>
                                <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                            </td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Grand Total (Incl. GST):</td>
                        <td class="text-end fw-bold text-ce-primary fs-5">₹{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Continue Shopping
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('checkout.invoice', $order->order_number) }}" target="_blank" class="btn btn-ce-primary">
                    <i class="bi bi-printer me-1"></i> Print Tax Invoice
                </a>
                @auth
                    <a href="{{ route('customer.orders') }}" class="btn btn-dark">
                        View My Orders
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
