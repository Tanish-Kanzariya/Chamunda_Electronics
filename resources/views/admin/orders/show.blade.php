@extends('layouts.admin')

@section('title', 'Manage Order ' . $order->order_number . ' - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Order #{{ $order->order_number }}</h3>
        <small class="text-secondary">Placed on {{ $order->created_at->format('d F Y, h:i A') }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('checkout.invoice', $order->order_number) }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i> Print Tax Invoice
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Order Details & Items -->
    <div class="col-lg-8">
        <!-- Customer & Delivery Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h5 class="fw-bold mb-3 pb-2 border-bottom">Customer & Shipping Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted text-uppercase fw-bold">Customer Name</div>
                    <div class="fw-bold fs-6">{{ $order->customer_name }}</div>
                    <div class="text-secondary small mt-1"><i class="bi bi-envelope me-1"></i> {{ $order->customer_email }}</div>
                    <div class="text-secondary small"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted text-uppercase fw-bold">Delivery Address</div>
                    <div class="text-secondary">{{ $order->shipping_address }}</div>
                    <div class="text-secondary">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
                    @if($order->notes)
                        <div class="p-2 bg-light rounded mt-2 small text-muted">
                            <strong>Site Notes:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ordered Items Table -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom">Ordered Items ({{ $order->items->count() }})</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Item</th>
                            <th>SKU</th>
                            <th>Rate (₹)</th>
                            <th>Qty</th>
                            <th class="text-end">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                </td>
                                <td><small class="text-muted">{{ $item->product_sku ?? 'N/A' }}</small></td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item->quantity }}</span></td>
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
                                <td colspan="4" class="text-end text-success">Discount ({{ $order->coupon_code }}):</td>
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
                            <td class="text-end fw-bold fs-6 text-primary">₹{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Status Update Control Box -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white sticky-top" style="top: 90px;">
            <h5 class="fw-bold mb-3 pb-2 border-bottom">Update Order Status</h5>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Order Progression</label>
                    <select name="order_status" class="form-select">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending (Received)</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing (Packaging)</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped (Dispatched)</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Courier Tracking AWB Number</label>
                    <input type="text" name="tracking_number" class="form-control" placeholder="e.g. DTDC89410293IN" value="{{ $order->tracking_number }}">
                    <small class="text-muted">Displayed to customer on tracking screen.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                    <i class="bi bi-check-circle me-1"></i> Update Order Status
                </button>
            </form>

            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order completely?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="bi bi-trash me-1"></i> Delete Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
