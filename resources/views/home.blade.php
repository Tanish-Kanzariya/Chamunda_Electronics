@extends('layouts.app')

@section('title', 'Chamunda Electrics - Premium Electrical Goods & Industrial Supplies')

@section('styles')
<style>
.hero-swiper-container {
    width: 100%;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 12px 30px -5px rgba(15, 76, 129, 0.2);
    background: #071b30;
}
.hero-swiper {
    width: 100%;
    border-radius: 1.25rem;
    overflow: hidden;
}
.hero-swiper .swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1.25rem;
    overflow: hidden;
    cursor: grab;
}
.hero-swiper .swiper-slide:active {
    cursor: grabbing;
}
.hero-swiper .swiper-slide img {
    width: 100%;
    height: auto;
    max-height: 480px;
    min-height: 190px;
    object-fit: cover;
    display: block;
    border-radius: 1.25rem;
    transition: transform 0.4s ease;
}
.hero-swiper .swiper-slide:hover img {
    transform: scale(1.006);
}
.hero-swiper .swiper-button-prev,
.hero-swiper .swiper-button-next {
    width: 48px;
    height: 48px;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    border-radius: 50%;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.35);
    transition: all 0.25s ease;
}
.hero-swiper .swiper-button-prev:after,
.hero-swiper .swiper-button-next:after {
    font-size: 17px;
    font-weight: 900;
}
.hero-swiper .swiper-button-prev:hover,
.hero-swiper .swiper-button-next:hover {
    background: var(--ce-accent);
    color: #0f172a;
    transform: scale(1.1);
}
.hero-swiper .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: #ffffff;
    opacity: 0.55;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.hero-swiper .swiper-pagination-bullet-active {
    width: 32px;
    border-radius: 6px;
    background: var(--ce-accent);
    opacity: 1;
}
</style>
@endsection

@section('content')
<!-- Hero Interactive Slider Section -->
<section class="py-3 py-lg-4">
    <div class="container">
        @if(isset($banners) && $banners->count() > 0)
            <div id="heroBannerCarousel" class="hero-swiper-container position-relative">
                <div class="swiper hero-swiper">
                    <div class="swiper-wrapper">
                        @foreach($banners as $index => $banner)
                            <div class="swiper-slide">
                                <a href="{{ $banner->link_url ?: route('shop.index') }}" class="d-block w-100 text-decoration-none">
                                    <img src="{{ $banner->image_url }}" class="img-fluid w-100" alt="{{ $banner->title ?? 'Chamunda Electrics Slide' }}">
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slide Pagination Indicator Bullets -->
                    <div class="swiper-pagination mb-2"></div>

                    <!-- Slide Navigation Arrows -->
                    <div class="swiper-button-prev ms-2"></div>
                    <div class="swiper-button-next me-2"></div>
                </div>
            </div>
        @else
            <!-- Fallback Static Hero if no sliders are active -->
            <div id="heroBannerCarousel" class="p-4 p-md-5 rounded-4 text-white position-relative overflow-hidden shadow" style="background: linear-gradient(135deg, #0b2545 0%, #0f4c81 50%, #1e293b 100%);">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Authorized Electrical Wholesale & Retail
                        </span>
                        <h1 class="display-5 fw-extrabold mb-3">Powering Homes, Projects & Industries</h1>
                        <p class="lead text-light mb-4">Discover top-rated Polycab wires, Havells geysers, Anchor switches, and Philips lighting.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-warning btn-lg px-4 py-2 text-dark fw-bold">Explore Catalog</a>
                    </div>
                </div>
            </div>
        @endif
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined' && document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: true,
            speed: 700,
            effect: 'slide',
            grabCursor: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            keyboard: {
                enabled: true,
            },
        });
    }
});
</script>
@endsection

