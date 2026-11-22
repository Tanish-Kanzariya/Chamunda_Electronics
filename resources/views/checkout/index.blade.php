@extends('layouts.app')

@section('title', 'Secure Checkout - Chamunda Electrics')

@section('styles')
<style>
.checkout-card {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.address-type-btn {
    cursor: pointer;
    border: 1.5px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 10px 14px;
    font-weight: 600;
    font-size: 0.85rem;
    text-align: center;
    transition: all 0.2s ease;
    background: #ffffff;
}
.address-type-btn.active, .address-type-btn:hover {
    border-color: var(--ce-primary);
    background-color: #f0f7ff;
    color: var(--ce-primary);
}
.payment-option-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 0.85rem;
    padding: 14px 18px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #ffffff;
}
.payment-option-card:hover, .payment-option-card.selected {
    border-color: var(--ce-primary);
    background-color: #f8fafc;
}
.payment-option-card.selected {
    box-shadow: 0 4px 12px rgba(15, 76, 129, 0.08);
}
.upi-badge {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 3px 8px;
    font-size: 0.75rem;
    font-weight: 700;
}
</style>
@endsection

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

    <!-- Buy Now Notification Banner -->
    @if($isBuyNow && isset($buyNowProduct))
        <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-lightning-charge-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Instant 1-Click Buy Now Mode</h6>
                    <div class="small text-secondary">
                        Checking out: <strong>{{ $buyNowProduct['name'] }}</strong> (Qty: {{ $buyNowProduct['quantity'] }}) &bull; ₹{{ number_format($buyNowProduct['price'] * $buyNowProduct['quantity'], 2) }}
                        @if(count($cart) > 0)
                            <span class="d-block d-sm-inline ms-sm-2 text-muted">(Your {{ count($cart) }} cart items remain saved)</span>
                        @endif
                    </div>
                </div>
            </div>
            @if(count($cart) > 0)
                <a href="{{ route('checkout.cancel-buy-now') }}" class="btn btn-outline-dark btn-sm text-nowrap fw-bold px-3">
                    <i class="bi bi-cart3 me-1"></i> Switch to Full Cart ({{ count($cart) }} Items)
                </a>
            @endif
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold mb-0">
            <i class="bi bi-shield-lock-fill text-success me-2"></i> Real-World Express Checkout
        </h3>
        <span class="badge bg-light text-secondary border px-3 py-2 small">
            <i class="bi bi-lock-fill text-success me-1"></i> 256-bit Bank-Grade Encryption
        </span>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="is_buy_now" value="{{ $isBuyNow ? 1 : 0 }}">

        <div class="row g-4">
            <!-- Left: Delivery, GST & Payment Information -->
            <div class="col-lg-7">
                <!-- Step 1: Delivery Address -->
                <div class="card checkout-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark">
                            <span class="badge bg-ce-primary rounded-circle me-2 px-2 py-1">1</span> Delivery &amp; Contact Details
                        </h5>
                        <small class="text-muted"><span class="text-danger">*</span> Required fields</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="e.g. Rahul Patel" value="{{ old('customer_name', $user->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-muted">+91</span>
                                <input type="tel" name="customer_phone" class="form-control" placeholder="9876543210" pattern="[0-9]{10}" maxlength="10" value="{{ old('customer_phone', preg_replace('/[^0-9]/', '', $user->phone ?? '')) }}" required>
                            </div>
                            <small class="text-muted">For delivery dispatch updates via SMS</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control" placeholder="name@example.com" value="{{ old('customer_email', $user->email ?? '') }}" required>
                            <small class="text-muted">GST Tax invoice &amp; tracking copy will be emailed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Alternate Contact Number (Optional)</label>
                            <input type="tel" name="alternate_phone" class="form-control" placeholder="e.g. 9825012345" value="{{ old('alternate_phone') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Full Street Address / Project Site <span class="text-danger">*</span></label>
                            <textarea name="shipping_address" rows="2" class="form-control" placeholder="Flat / Shop No., Building / Site Name, GIDC Road or Street" required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nearby Landmark (Optional)</label>
                            <input type="text" name="landmark" class="form-control" placeholder="e.g. Near Bus Station, Behind Temple" value="{{ old('landmark') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" name="pincode" class="form-control" placeholder="380001" pattern="[0-9]{6}" maxlength="6" value="{{ old('pincode', $user->pincode ?? '380001') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city ?? 'Ahmedabad') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">State <span class="text-danger">*</span></label>
                            <select name="state" class="form-select" required>
                                @php
                                    $states = ['Gujarat', 'Maharashtra', 'Rajasthan', 'Madhya Pradesh', 'Delhi', 'Haryana', 'Punjab', 'Uttar Pradesh', 'Karnataka', 'Tamil Nadu', 'Telangana', 'West Bengal', 'Andhra Pradesh', 'Kerala', 'Goa'];
                                    $selectedState = old('state', $user->state ?? 'Gujarat');
                                @endphp
                                @foreach($states as $st)
                                    <option value="{{ $st }}" {{ $selectedState === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Address Type Selection -->
                        <div class="col-12 mt-2">
                            <label class="form-label small fw-bold mb-2">Address Type</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="address_type" id="type_home" value="home" checked>
                                    <label class="btn address-type-btn w-100" for="type_home">
                                        <i class="bi bi-house-door-fill me-1"></i> Home
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="address_type" id="type_site" value="commercial_site">
                                    <label class="btn address-type-btn w-100" for="type_site">
                                        <i class="bi bi-tools me-1"></i> Site / Project
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="address_type" id="type_office" value="office_warehouse">
                                    <label class="btn address-type-btn w-100" for="type_office">
                                        <i class="bi bi-building me-1"></i> Office
                                    </label>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="save_profile_address" id="saveProfile" value="1" checked>
                                    <label class="form-check-label small" for="saveProfile">
                                        Save/Update this delivery address in my Chamunda Electrics profile
                                    </label>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Step 2: Business & Contractor GST Invoicing -->
                <div class="card checkout-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">2</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Business / Contractor GST Invoice</h6>
                                <small class="text-muted">Claim 18% GST Input Tax Credit (ITC)</small>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_gst" id="gstToggle" value="1" onchange="toggleGstFields(this.checked)">
                        </div>
                    </div>

                    <div id="gstFieldsContainer" class="d-none mt-3 pt-3 border-top">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Company / Contractor Name</label>
                                <input type="text" name="company_name" class="form-control form-control-sm" placeholder="e.g. Patel Electricals &amp; Contractors">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">GSTIN (15 Digits)</label>
                                <input type="text" name="gstin" class="form-control form-control-sm text-uppercase" placeholder="24AAAAA0000A1Z5" maxlength="15">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle text-primary me-1"></i> Your printable GST tax invoice with HSN codes will reflect your registered business details.
                        </small>
                    </div>
                </div>

                <!-- Step 3: Dispatch & Shipping Speed -->
                <div class="card checkout-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <span class="badge bg-ce-primary rounded-circle me-2 px-2 py-1">3</span> Delivery Speed
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        <label class="payment-option-card selected d-flex align-items-center justify-content-between cursor-pointer">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="delivery_type" value="standard" checked>
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-truck text-primary me-2"></i> Standard Ground Delivery (3-5 Days)</div>
                                    <small class="text-muted">Doorstep delivery via insured logistics partners</small>
                                </div>
                            </div>
                            <span class="fw-bold {{ $shipping == 0 ? 'text-success' : 'text-dark' }}">
                                {{ $shipping == 0 ? 'FREE' : '₹99.00' }}
                            </span>
                        </label>

                        <label class="payment-option-card d-flex align-items-center justify-content-between cursor-pointer">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="delivery_type" value="express">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-lightning-fill text-warning me-2"></i> Priority Site Dispatch (Next-Day)</div>
                                    <small class="text-muted">Priority warehouse packing &amp; express same-day courier handover</small>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">₹149.00</span>
                        </label>
                    </div>
                </div>

                <!-- Step 4: Real-World Payment Methods -->
                <div class="card checkout-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <span class="badge bg-ce-primary rounded-circle me-2 px-2 py-1">4</span> Select Payment Method
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        <!-- Option 1: COD -->
                        <div class="payment-option-card selected" onclick="selectPayment('cod')">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="cod" id="pay_cod" checked>
                                <label class="form-check-label w-100 cursor-pointer" for="pay_cod">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark"><i class="bi bi-cash-stack text-success me-2 fs-5"></i> Cash on Delivery (COD)</div>
                                            <small class="text-muted">Pay with cash or UPI QR directly to delivery agent on arrival.</small>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">Verified</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Option 2: Instant UPI -->
                        <div class="payment-option-card" onclick="selectPayment('online_upi')">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="online_upi" id="pay_upi">
                                <label class="form-check-label w-100 cursor-pointer" for="pay_upi">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark"><i class="bi bi-qr-code-scan text-primary me-2 fs-5"></i> Instant UPI / QR Payment</div>
                                            <div class="d-flex gap-1 mt-1">
                                                <span class="upi-badge">GPay</span>
                                                <span class="upi-badge">PhonePe</span>
                                                <span class="upi-badge">Paytm</span>
                                                <span class="upi-badge">BHIM</span>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">Zero Extra Fee</span>
                                    </div>
                                </label>
                            </div>
                            <div id="upiExtraFields" class="d-none mt-3 pt-3 border-top">
                                <label class="form-label small fw-bold">Enter Your UPI ID</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" name="upi_id" class="form-control" placeholder="yourname@okhdfcbank" value="{{ old('upi_id') }}">
                                    <button class="btn btn-outline-primary" type="button">Verify</button>
                                </div>
                                <small class="text-muted"><i class="bi bi-check-circle text-success me-1"></i> You will receive a payment request notification on your UPI app.</small>
                            </div>
                        </div>

                        <!-- Option 3: Credit/Debit Card -->
                        <div class="payment-option-card" onclick="selectPayment('card')">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="card" id="pay_card">
                                <label class="form-check-label w-100 cursor-pointer" for="pay_card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark"><i class="bi bi-credit-card-2-front-fill text-info me-2 fs-5"></i> Debit / Credit Card</div>
                                            <small class="text-muted">Visa, MasterCard, RuPay, Maestro supported.</small>
                                        </div>
                                        <span class="badge bg-light text-dark border">All Banks</span>
                                    </div>
                                </label>
                            </div>
                            <div id="cardExtraFields" class="d-none mt-3 pt-3 border-top">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Card Number</label>
                                        <input type="text" name="card_number" class="form-control form-control-sm" placeholder="4532 &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 8921" maxlength="19">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Expiry Date</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="MM / YY" maxlength="5">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">CVV</label>
                                        <input type="password" class="form-control form-control-sm" placeholder="&bull;&bull;&bull;" maxlength="4">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Option 4: Net Banking -->
                        <div class="payment-option-card" onclick="selectPayment('netbanking')">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input mt-0 me-3" type="radio" name="payment_method" value="netbanking" id="pay_netbanking">
                                <label class="form-check-label w-100 cursor-pointer" for="pay_netbanking">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark"><i class="bi bi-bank text-warning me-2 fs-5"></i> Net Banking</div>
                                            <small class="text-muted">Direct transfer from all major Indian banks</small>
                                        </div>
                                        <span class="badge bg-light text-dark border">50+ Banks</span>
                                    </div>
                                </label>
                            </div>
                            <div id="netbankingExtraFields" class="d-none mt-3 pt-3 border-top">
                                <label class="form-label small fw-bold">Select Your Bank</label>
                                <select name="bank_name" class="form-select form-select-sm">
                                    <option value="SBI">State Bank of India (SBI)</option>
                                    <option value="HDFC">HDFC Bank</option>
                                    <option value="ICICI">ICICI Bank</option>
                                    <option value="Axis">Axis Bank</option>
                                    <option value="Kotak">Kotak Mahindra Bank</option>
                                    <option value="BOB">Bank of Baroda</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Items & Real-World Pricing Breakdown -->
            <div class="col-lg-5">
                <div class="card checkout-card p-4 sticky-top" style="top: 85px;">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold mb-0">
                            Order Summary ({{ count($items) }} {{ count($items) === 1 ? 'Item' : 'Items' }})
                        </h5>
                        @if($isBuyNow)
                            <span class="badge bg-warning text-dark fw-bold">Buy Now</span>
                        @endif
                    </div>

                    <!-- Items List -->
                    <div class="mb-3" style="max-height: 260px; overflow-y: auto;">
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image'] ?? 'images/products/polycab-flamegard-wire.svg') }}" width="48" height="48" class="rounded border p-1 bg-light object-fit-contain">
                                    <div>
                                        <span class="d-block text-truncate fw-semibold small" style="max-width: 190px;" title="{{ $item['name'] }}">
                                            {{ $item['name'] }}
                                        </span>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }} &times; ₹{{ number_format($item['price'], 2) }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold small text-ce-primary">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Promo Coupon Box -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <label class="form-label small fw-bold mb-1"><i class="bi bi-tag-fill text-danger me-1"></i> Have a Promo Coupon?</label>
                        @if($coupon)
                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border">
                                <span class="badge bg-success font-monospace">{{ $coupon['code'] }}</span>
                                <span class="text-success small fw-bold">Applied!</span>
                                <a href="{{ route('cart.remove-coupon') }}" class="btn btn-link btn-sm text-danger text-decoration-none p-0">Remove</a>
                            </div>
                        @else
                            <form action="{{ route('cart.apply-coupon') }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <input type="text" name="coupon_code" class="form-control form-control-sm text-uppercase" placeholder="e.g. CHAMUNDA10">
                                <button type="submit" class="btn btn-dark btn-sm text-nowrap fw-bold px-3">Apply</button>
                            </form>
                            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Try code: <code>CHAMUNDA10</code> (10% off)</small>
                        @endif
                    </div>

                    <!-- Price Calculations -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Items Subtotal</span>
                        <span class="fw-semibold">₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span><i class="bi bi-check-circle-fill me-1"></i> Coupon Discount</span>
                            <span class="fw-bold">- ₹{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-1 text-muted small">
                        <span>CGST (9%)</span>
                        <span>₹{{ number_format($cgst, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>SGST (9%)</span>
                        <span>₹{{ number_format($sgst, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Total GST (18% Included)</span>
                        <span class="fw-semibold">₹{{ number_format($tax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Doorstep Shipping</span>
                        @if($shipping == 0)
                            <span class="text-success fw-bold"><i class="bi bi-patch-check-fill me-1"></i> FREE</span>
                        @else
                            <span class="fw-semibold">₹{{ number_format($shipping, 2) }}</span>
                        @endif
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <div>
                            <span class="fw-bold fs-5 text-dark d-block">Grand Total</span>
                            <small class="text-muted">Inclusive of all taxes &amp; shipping</small>
                        </div>
                        <span class="display-6 fw-bold text-ce-primary">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn btn-ce-accent btn-lg w-100 fw-bold shadow-sm py-3 text-uppercase">
                        Confirm &amp; Place Order <i class="bi bi-shield-check ms-1"></i>
                    </button>

                    <div class="text-center mt-3 small text-secondary">
                        <i class="bi bi-truck text-primary me-1"></i> Same-Day Dispatch &bull; <i class="bi bi-receipt text-warning me-1"></i> Official Tax Invoice
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function toggleGstFields(show) {
    const container = document.getElementById('gstFieldsContainer');
    if (show) {
        container.classList.remove('d-none');
    } else {
        container.classList.add('d-none');
    }
}

function selectPayment(method) {
    document.querySelectorAll('.payment-option-card').forEach(c => c.classList.remove('selected'));
    const input = document.getElementById('pay_' + method);
    if (input) {
        input.checked = true;
        input.closest('.payment-option-card').classList.add('selected');
    }

    // Toggle extra fields
    document.getElementById('upiExtraFields')?.classList.toggle('d-none', method !== 'online_upi');
    document.getElementById('cardExtraFields')?.classList.toggle('d-none', method !== 'card');
    document.getElementById('netbankingExtraFields')?.classList.toggle('d-none', method !== 'netbanking');
}
</script>
@endsection
