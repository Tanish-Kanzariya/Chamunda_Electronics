@extends('layouts.app')

@section('title', 'My Wishlist - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h4 class="fw-bold mb-0"><i class="bi bi-heart-fill text-danger me-2"></i> Saved Wishlist ({{ $wishlists->count() }})</h4>
            <a href="{{ route('shop.index') }}" class="btn btn-ce-primary btn-sm">Browse More</a>
        </div>

        @if($wishlists->count() > 0)
            <div class="row g-4">
                @foreach($wishlists as $wish)
                    @php $prod = $wish->product; @endphp
                    @if($prod)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="product-card p-3 position-relative">
                                <form action="{{ route('customer.wishlist.toggle') }}" method="POST" class="position-absolute top-0 end-0 m-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm text-danger" title="Remove from Wishlist">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <a href="{{ route('product.show', $prod->slug) }}" class="d-block text-center mb-3">
                                    <img src="{{ asset($prod->image ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $prod->name }}" class="img-fluid rounded" style="height: 160px; object-fit: contain;">
                                </a>

                                <span class="badge bg-light text-secondary border small mb-1">{{ $prod->brand->name ?? 'Chamunda' }}</span>
                                <h6 class="fw-bold mb-2 text-truncate" title="{{ $prod->name }}">
                                    <a href="{{ route('product.show', $prod->slug) }}" class="text-decoration-none text-dark">{{ $prod->name }}</a>
                                </h6>

                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex align-items-baseline gap-2 mb-3">
                                        <span class="fs-5 fw-bold text-ce-primary">₹{{ number_format($prod->effective_price, 2) }}</span>
                                        @if($prod->sale_price)
                                            <span class="text-muted text-decoration-line-through small">₹{{ number_format($prod->price, 2) }}</span>
                                        @endif
                                    </div>

                                    @if($prod->in_stock)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-ce-primary w-100 fw-semibold btn-sm">
                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100 btn-sm disabled" disabled>Out of Stock</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-heart display-2 text-secondary mb-3"></i>
                <h5 class="fw-bold">Your wishlist is empty</h5>
                <p>Save items you are interested in while browsing our store.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-ce-primary px-4 fw-bold">Explore Products</a>
            </div>
        @endif
    </div>
</div>
@endsection
