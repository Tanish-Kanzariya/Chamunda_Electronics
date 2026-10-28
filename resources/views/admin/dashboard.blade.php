@extends('layouts.admin')

@section('title', 'Admin Dashboard - Chamunda Electrics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Overview & Business Analytics</h3>
        <small class="text-secondary">Welcome to Chamunda Electrics administrative management suite.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-warning fw-semibold btn-sm text-dark">
            <i class="bi bi-sliders me-1"></i> Hero Sliders
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary fw-semibold btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary fw-semibold btn-sm">
            <i class="bi bi-box-seam me-1"></i> Manage Orders
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Total Sales Revenue</small>
                    <h3 class="fw-bold mb-0 text-success mt-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-4">
                    <i class="bi bi-currency-rupee fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Total Orders</small>
                    <h3 class="fw-bold mb-0 text-primary mt-1">{{ $totalOrders }}</h3>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-4">
                    <i class="bi bi-bag-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Active Products</small>
                    <h3 class="fw-bold mb-0 text-warning mt-1">{{ $totalProducts }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-4">
                    <i class="bi bi-cpu-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Registered Customers</small>
                    <h3 class="fw-bold mb-0 text-info mt-1">{{ $totalCustomers }}</h3>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-4">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Quick Pills -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <span class="fw-bold text-secondary small text-uppercase"><i class="bi bi-funnel me-1"></i> Order Pipeline Status:</span>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="badge bg-warning text-dark px-3 py-2 text-decoration-none">
                Pending: {{ $orderStatusCounts['pending'] }}
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="badge bg-info text-dark px-3 py-2 text-decoration-none">
                Processing: {{ $orderStatusCounts['processing'] }}
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="badge bg-primary text-white px-3 py-2 text-decoration-none">
                Shipped: {{ $orderStatusCounts['shipped'] }}
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="badge bg-success text-white px-3 py-2 text-decoration-none">
                Delivered: {{ $orderStatusCounts['delivered'] }}
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="badge bg-danger text-white px-3 py-2 text-decoration-none">
                Cancelled: {{ $orderStatusCounts['cancelled'] }}
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders Table -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-0">Recent Inward Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="small fw-semibold text-decoration-none">View All Orders</a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td class="fw-bold">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td><span class="badge {{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span></td>
                                    <td><span class="badge {{ $order->status_badge_class }}">{{ ucfirst($order->order_status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">No orders found yet.</div>
            @endif
        </div>
    </div>

    <!-- Low Stock Inventory Alerts -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock Alerts</h5>
                <a href="{{ route('admin.products.index') }}" class="small fw-semibold text-decoration-none">Catalog</a>
            </div>

            @if($lowStockProducts->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($lowStockProducts as $low)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded border bg-light">
                            <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                                <img src="{{ asset($low->image ?? 'images/products/polycab-flamegard-wire.svg') }}" width="40" height="40" class="rounded border bg-white object-fit-contain">
                                <div class="text-truncate" style="max-width: 140px;">
                                    <div class="fw-semibold small text-truncate">{{ $low->name }}</div>
                                    <small class="text-muted">SKU: {{ $low->sku }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger">{{ $low->stock_quantity }} units left</span>
                                <a href="{{ route('admin.products.edit', $low->id) }}" class="d-block small text-primary text-decoration-none mt-1">Restock</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">All stock levels are optimal.</div>
            @endif
        </div>
    </div>
</div>
@endsection
