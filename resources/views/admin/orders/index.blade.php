@extends('layouts.admin')

@section('title', 'Manage Orders - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Customer & Contractor Orders</h3>
        <small class="text-secondary">Process customer orders, update dispatch status, and manage invoices.</small>
    </div>
</div>

<!-- Filters & Search -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <div class="d-flex flex-wrap gap-2 mb-3 pb-3 border-bottom">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All Orders
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') == 'processing' ? 'btn-info text-dark' : 'btn-outline-info text-dark' }}">
            Processing
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="btn btn-sm {{ request('status') == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
            Shipped
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
            Delivered
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
            Cancelled
        </a>
    </div>

    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by Order #, Customer Name, Phone, Email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100 fw-semibold">Search</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>City / Destination</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $order->order_number }}
                            </a>
                            <small class="d-block text-muted">{{ $order->items->count() }} items</small>
                        </td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                            <small class="text-muted">{{ $order->customer_phone }}</small>
                        </td>
                        <td>
                            <div>{{ $order->city }}</div>
                            <small class="text-muted">{{ $order->pincode }}</small>
                        </td>
                        <td class="fw-bold text-ce-primary">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span>
                            <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $order->payment_method) }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $order->status_badge_class }}">{{ ucfirst($order->order_status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Manage Order">
                                    <i class="bi bi-pencil-square"></i> Manage
                                </a>
                                <a href="{{ route('checkout.invoice', $order->order_number) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print Invoice">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No orders found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
