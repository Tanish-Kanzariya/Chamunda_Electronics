@extends('layouts.admin')

@section('title', 'Manage Coupons - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Coupons & Promotional Codes</h3>
        <small class="text-secondary">Create and manage contractor discounts and seasonal promotion codes.</small>
    </div>
    <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#createCouponModal">
        <i class="bi bi-plus-circle me-1"></i> Add Coupon
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Coupon Code</th>
                    <th>Discount Type</th>
                    <th>Value</th>
                    <th>Min. Order</th>
                    <th>Max Discount</th>
                    <th>Expires At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><span class="badge bg-dark text-warning fs-6 px-3 py-1 font-monospace">{{ $coupon->code }}</span></td>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($coupon->type) }}</span></td>
                        <td class="fw-bold">
                            @if($coupon->type === 'percent')
                                {{ $coupon->value }}%
                            @else
                                ₹{{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>₹{{ number_format($coupon->min_order_amount, 2) }}</td>
                        <td>{{ $coupon->max_discount ? '₹' . number_format($coupon->max_discount, 2) : 'No Limit' }}</td>
                        <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Never' }}</td>
                        <td>
                            @if($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete coupon?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No coupons created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCouponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New Coupon Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase font-monospace" placeholder="e.g. SUMMER15" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₹)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="value" class="form-control" placeholder="e.g. 10" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Min. Order Amount (₹)</label>
                            <input type="number" step="0.01" name="min_order_amount" class="form-control" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max Discount Limit (₹)</label>
                            <input type="number" step="0.01" name="max_discount" class="form-control" placeholder="Optional cap">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="couponActive" checked>
                        <label class="form-check-label small fw-bold" for="couponActive">Active immediately</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
