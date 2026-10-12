@extends('layouts.app')

@section('title', 'Chamunda Electrics - Premium Electrical Goods & Industrial Supplies')

@section('content')
<!-- Hero Section -->
<section class="py-4 py-lg-5">
    <div class="container">
        <div class="p-4 p-md-5 rounded-4 text-white position-relative overflow-hidden shadow" style="background: linear-gradient(135deg, #0b2545 0%, #0f4c81 50%, #1e293b 100%);">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Authorized Electrical Wholesale & Retail
                    </span>
                    <h1 class="display-5 fw-extrabold mb-3 lh-sm">
                        Powering Homes, Projects & Industries with 100% Genuine Supplies
                    </h1>
                    <p class="lead text-light mb-4">
                        Discover top-rated Polycab wires, Havells geysers, Anchor modular switches, Philips LED lighting, and BLDC energy-saving fans at wholesale direct rates.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('shop.index') }}" class="btn btn-ce-accent btn-lg px-4 py-2 shadow-sm">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Explore Catalog
                        </a>
                        <a href="{{ route('shop.index', ['sort' => 'price_low']) }}" class="btn btn-outline-light btn-lg px-4 py-2">
                            <i class="bi bi-tag-fill me-1"></i> Special Offers
                        </a>
                    </div>
                    <div class="mt-4 pt-3 border-top border-secondary d-flex flex-wrap gap-4 text-light small">
                        <div><i class="bi bi-check-circle-fill text-warning me-1"></i> ISI Certified Quality</div>
                        <div><i class="bi bi-check-circle-fill text-warning me-1"></i> Same-Day Dispatch</div>
                        <div><i class="bi bi-check-circle-fill text-warning me-1"></i> Full Manufacturer Warranty</div>
                    </div>
                </div>
                <div class="col-lg-5 text-center mt-4 mt-lg-0">
                    <img src="{{ asset('images/products/crompton-energion-bldc-fan.svg') }}" alt="Electrical Appliance" class="img-fluid rounded-4 shadow-lg border border-secondary" style="max-height: 320px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-uppercase text-warning fw-bold mb-1">Browse Categories</h6>
                <h3 class="fw-bold mb-0">Shop by Department</h3>
            </div>
            <a href="{{ route('shop.index') }}" class="text-decoration-none fw-semibold text-ce-primary">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3 hover-shadow" style="transition: transform 0.2s; background: #ffffff;">
                            <div class="p-3 mb-2 d-flex justify-content-center align-items-center" style="height: 120px;">
                                <img src="{{ asset($category->image ?? 'images/categories/wires-cables.svg') }}" alt="{{ $category->name }}" class="img-fluid" style="max-height: 100px;">
                            </div>
                            <h6 class="fw-bold mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} Products</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-uppercase text-primary fw-bold mb-1">Handpicked Selections</h6>
                <h3 class="fw-bold mb-0">Featured Electrical Products</h3>
            </div>
            <a href="{{ route('shop.index') }}" class="text-decoration-none fw-semibold text-ce-primary">
                Explore All Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card p-3 position-relative">
                        @if($product->discount_percentage)
                            <span class="badge-discount position-absolute top-0 start-0 m-3">
                                {{ $product->discount_percentage }}% OFF
                            </span>
                        @endif

                        <!-- Product Thumbnail -->
                        <a href="{{ route('product.show', $product->slug) }}" class="d-block text-center mb-3">
                            <img src="{{ asset($product->image ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $product->name }}" class="img-fluid rounded" style="height: 180px; object-fit: contain;">
                        </a>

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted fw-bold">{{ $product->brand->name ?? 'Chamunda' }}</small>
                            <small class="text-warning">
                                <i class="bi bi-star-fill"></i> {{ $product->average_rating }}
                            </small>
                        </div>

                        <h6 class="fw-bold mb-2 text-truncate" title="{{ $product->name }}">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h6>

                        <div class="mt-auto pt-2 border-top">
                            <div class="d-flex align-items-baseline gap-2 mb-3">
                                <span class="fs-5 fw-bold text-ce-primary">₹{{ number_format($product->effective_price, 2) }}</span>
                                @if($product->sale_price)
                                    <span class="text-muted text-decoration-line-through small">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-ce-primary w-100 fw-semibold">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Promotional Coupon & Contractor Banner -->
<section class="py-5">
    <div class="container">
        <div class="p-4 p-md-5 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border: 1px solid #fde68a;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-danger text-white px-3 py-2 fw-bold text-uppercase mb-2">Exclusive Offer</span>
                    <h2 class="fw-extrabold text-dark mb-2">Get 10% Instant Discount on Your First Order!</h2>
                    <p class="text-secondary mb-3 fs-6">
                        Use coupon code <span class="badge bg-dark text-warning fs-6 px-3 py-1 font-monospace">CHAMUNDA10</span> at checkout on orders above ₹1,000.
                        For large building wiring and contractor bulk orders, enjoy direct invoice tax credits!
                    </p>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="fw-bold text-dark"><i class="bi bi-tag-fill text-danger me-1"></i> More Codes:</span>
                        <span class="badge bg-white text-dark border px-2 py-1">FLAT500 (₹500 OFF on ₹5000+)</span>
                        <span class="badge bg-white text-dark border px-2 py-1">DIWALI20 (20% OFF on ₹3000+)</span>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('shop.index') }}" class="btn btn-dark btn-lg fw-bold px-4">
                        Claim Discount Now <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Brands -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="text-center mb-4">
            <h6 class="text-uppercase text-secondary fw-bold">Official Authorized Partner</h6>
            <h3 class="fw-bold">India's Most Trusted Electrical Brands</h3>
        </div>

        <div class="row g-3 justify-content-center align-items-center text-center">
            @foreach($brands as $brand)
                <div class="col-4 col-md-3 col-lg-2">
                    <a href="{{ route('shop.index', ['brand' => $brand->slug]) }}" class="d-block p-2 text-decoration-none">
                        <img src="{{ asset($brand->logo ?? 'images/brands/polycab.svg') }}" alt="{{ $brand->name }}" class="img-fluid rounded border p-2 bg-light hover-shadow" style="max-height: 60px;">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-uppercase text-warning fw-bold">Customer Testimonials</h6>
            <h3 class="fw-bold">What Electricians & Homeowners Say</h3>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="text-warning mb-2 fs-5">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted">
                        "Chamunda Electrics is our go-to wholesale distributor for all our residential wiring projects. 100% genuine Polycab copper coils, proper GST invoices, and prompt dispatch."
                    </p>
                    <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            MK
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Mukesh Kumar</h6>
                            <small class="text-muted">Licensed Electrical Contractor</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="text-warning mb-2 fs-5">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted">
                        "Ordered Crompton BLDC fans and Havells geysers for our new bungalow in Surat. Received everything nicely packed in under 48 hours. Excellent service and support!"
                    </p>
                    <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            RP
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Rahul Patel</h6>
                            <small class="text-muted">Homeowner, Surat</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="text-warning mb-2 fs-5">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted">
                        "Fair prices on Anchor Roma modular switches and Schneider MCBs. The staff is technically knowledgeable and guides you with correct ratings for heavy appliances."
                    </p>
                    <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            JS
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Jitendra Shah</h6>
                            <small class="text-muted">Interior Project Consultant</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
