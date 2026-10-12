@extends('layouts.app')

@section('title', 'Checkout - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-credit-card-2-front-fill text-ce-primary me-2"></i> Secure Checkout</h3>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Left: Delivery & Customer Info -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">1. Contact & Delivery Details</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $user->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('customer_phone', $user->phone ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $user->email ?? '') }}" required>
                            <small class="text-muted">Order confirmation and invoice will be sent here.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Delivery Address (House / Flat No, Building / Site Name, Street) <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" rows="3" class="form-control" placeholder="Complete site or residential delivery address" required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city ?? 'Ahmedabad') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $user->state ?? 'Gujarat') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" name="pincode" class="form-control" placeholder="380001" value="{{ old('pincode', $user->pincode ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Order / Site Instructions (Optional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="e.g. Call before delivery, deliver to contractor at site" value="{{ old('notes') }}">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">2. Payment Method</h5>

                    <div class="d-flex flex-column gap-3">
                        <div class="border rounded-3 p-3 d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="cod" id="pay_cod" checked>
                            <label class="form-check-label w-100 cursor-pointer" for="pay_cod">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark"><i class="bi bi-cash-stack text-success me-2"></i> Cash on Delivery (COD)</div>
                                        <small class="text-muted">Pay in cash or UPI to delivery agent upon receiving goods.</small>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Recommended</span>
                                </div>
                            </label>
                        </div>

                        <div class="border rounded-3 p-3 d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="online_upi" id="pay_upi">
                            <label class="form-check-label w-100 cursor-pointer" for="pay_upi">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark"><i class="bi bi-qr-code-scan text-primary me-2"></i> Instant UPI / QR Payment</div>
                                        <small class="text-muted">GPay, PhonePe, Paytm, BHIM</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">Fast & Direct</span>
                                </div>
                            </label>
                        </div>

                        <div class="border rounded-3 p-3 d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="card" id="pay_card">
                            <label class="form-check-label w-100 cursor-pointer" for="pay_card">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-credit-card-2-front text-info me-2"></i> Debit / Credit Card / NetBanking</div>
                                    <small class="text-muted">Visa, MasterCard, RuPay cards supported.</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Review & Submit -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">Order Summary ({{ count($cart) }} Items)</h5>

                    <div class="mb-3" style="max-height: 240px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image'] ?? 'images/products/polycab-flamegard-wire.svg') }}" width="45" height="45" class="rounded border p-1 bg-light object-fit-contain">
                                    <div>
                                        <span class="d-block text-truncate fw-semibold small" style="max-width: 200px;">{{ $item['name'] }}</span>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }} × ₹{{ number_format($item['price'], 2) }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold small text-ce-primary">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Subtotal</span>
                        <span class="fw-semibold">₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Coupon ({{ $coupon['code'] }})</span>
                            <span class="fw-bold">- ₹{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">GST (18%)</span>
                        <span class="fw-semibold">₹{{ number_format($tax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Delivery</span>
                        @if($shipping == 0)
                            <span class="text-success fw-bold">FREE</span>
                        @else
                            <span class="fw-semibold">₹{{ number_format($shipping, 2) }}</span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold fs-5">Grand Total</span>
                        <span class="display-6 fw-bold text-ce-primary">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn btn-ce-accent btn-lg w-100 fw-bold shadow-sm">
                        Place Order <i class="bi bi-shield-check ms-1"></i>
                    </button>

                    <div class="text-center mt-3 small text-muted">
                        <i class="bi bi-lock-fill text-secondary me-1"></i> By ordering, you agree to Chamunda Electrics Terms of Service.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
