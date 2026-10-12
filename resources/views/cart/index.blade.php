@extends('layouts.app')

@section('title', 'Shopping Cart - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 text-ce-primary me-2"></i> Your Electrical Shopping Cart</h3>

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items Table -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th style="min-width: 250px;">Product</th>
                                    <th>Price</th>
                                    <th style="min-width: 140px;">Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset($item['image'] ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $item['name'] }}" width="60" height="60" class="rounded border p-1 object-fit-contain bg-light">
                                                <div>
                                                    <a href="{{ route('product.show', $item['slug']) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                        {{ $item['name'] }}
                                                    </a>
                                                    <small class="text-muted">SKU: {{ $item['sku'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold">₹{{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="100" class="form-control form-control-sm text-center fw-bold" style="width: 70px;">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Update Quantity">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-ce-primary">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                <button type="submit" class="btn btn-sm text-danger" title="Remove Item">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Clear entire cart?')">
                            <i class="bi bi-trash me-1"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">Order Summary</h5>

                    <!-- Coupon Input -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Have a Promo / Contractor Code?</label>
                        @if($coupon)
                            <div class="d-flex justify-content-between align-items-center p-2 bg-success-subtle text-success rounded border border-success-subtle mb-2">
                                <span class="small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Code: {{ $coupon['code'] }} Applied</span>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm text-danger p-0" title="Remove Coupon">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.apply-coupon') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="coupon_code" class="form-control form-control-sm text-uppercase fw-bold" placeholder="Enter coupon code" required>
                                <button type="submit" class="btn btn-dark btn-sm fw-bold">Apply</button>
                            </form>
                        @endif
                    </div>

                    <!-- Breakdown -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Subtotal</span>
                        <span class="fw-semibold">₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Coupon Discount</span>
                            <span class="fw-bold">- ₹{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">GST Tax (18% Electrical)</span>
                        <span class="fw-semibold">₹{{ number_format($tax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Shipping Charge</span>
                        @if($shipping == 0)
                            <span class="text-success fw-bold">FREE</span>
                        @else
                            <span class="fw-semibold">₹{{ number_format($shipping, 2) }}</span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold fs-5">Estimated Total</span>
                        <span class="display-6 fw-bold text-ce-primary">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-ce-primary btn-lg w-100 fw-bold shadow-sm">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                    <div class="text-center mt-3 small text-muted">
                        <i class="bi bi-shield-lock-fill text-success me-1"></i> 256-Bit SSL Encrypted Checkout
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center p-5 my-4">
            <i class="bi bi-cart-x text-muted display-2 mb-3"></i>
            <h4 class="fw-bold">Your Cart is Currently Empty</h4>
            <p class="text-muted mb-4">Explore our wide catalog of wires, switches, LEDs, and electrical accessories.</p>
            <div>
                <a href="{{ route('shop.index') }}" class="btn btn-ce-primary btn-lg px-4 fw-bold">
                    Start Shopping Now
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
