@extends('layouts.app')

@section('title', 'Electrical Catalog & Supplies - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shop Electrical Supplies</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-funnel-fill text-ce-primary me-2"></i> Filters</h5>
                    @if(request()->hasAny(['q', 'category', 'brand', 'min_price', 'max_price', 'in_stock']))
                        <a href="{{ route('shop.index') }}" class="text-danger small fw-semibold text-decoration-none">Clear All</a>
                    @endif
                </div>

                <form action="{{ route('shop.index') }}" method="GET">
                    <!-- Search query preserve -->
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Categories</h6>
                        <div class="d-flex flex-column gap-1" style="max-height: 220px; overflow-y: auto;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="" id="cat_all" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small" for="cat_all">All Categories</label>
                            </div>
                            @foreach($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}" id="cat_{{ $cat->id }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small d-flex justify-content-between" for="cat_{{ $cat->id }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-muted">({{ $cat->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Brands</h6>
                        <div class="d-flex flex-column gap-1" style="max-height: 200px; overflow-y: auto;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brand" value="" id="brand_all" {{ !request('brand') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small" for="brand_all">All Brands</label>
                            </div>
                            @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="brand" value="{{ $brand->slug }}" id="brand_{{ $brand->id }}" {{ request('brand') === $brand->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small d-flex justify-content-between" for="brand_{{ $brand->id }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="text-muted">({{ $brand->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Price (₹)</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- In Stock Checkbox -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="in_stock" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label small fw-semibold" for="in_stock">
                            In Stock Only
                        </label>
                    </div>

                    <button type="submit" class="btn btn-ce-primary btn-sm w-100 fw-bold">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid Area -->
        <div class="col-lg-9">
            <!-- Header bar with sorting & counts -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white p-3 rounded-4 shadow-sm mb-4">
                <div>
                    <h5 class="fw-bold mb-1">
                        @if(request('q'))
                            Search Results for: "<span class="text-ce-primary">{{ request('q') }}</span>"
                        @elseif(request('category'))
                            Category: {{ ucwords(str_replace('-', ' ', request('category'))) }}
                        @elseif(request('brand'))
                            Brand: {{ ucwords(str_replace('-', ' ', request('brand'))) }}
                        @else
                            All Electrical Supplies
                        @endif
                    </h5>
                    <small class="text-muted">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} items</small>
                </div>

                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    <label class="small text-muted text-nowrap">Sort by:</label>
                    <form action="{{ route('shop.index') }}" method="GET" id="sortForm">
                        @foreach(request()->except('sort') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products Listing -->
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="product-card p-3 position-relative">
                                @if($product->discount_percentage)
                                    <span class="badge-discount position-absolute top-0 start-0 m-3">
                                        {{ $product->discount_percentage }}% OFF
                                    </span>
                                @endif

                                @auth
                                    <form action="{{ route('customer.wishlist.toggle') }}" method="POST" class="position-absolute top-0 end-0 m-3">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Add to Wishlist">
                                            <i class="bi bi-heart text-danger"></i>
                                        </button>
                                    </form>
                                @endauth

                                <!-- Product Thumbnail -->
                                <a href="{{ route('product.show', $product->slug) }}" class="d-block text-center mb-3">
                                    <img src="{{ asset($product->image ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $product->name }}" class="img-fluid rounded" style="height: 180px; object-fit: contain;">
                                </a>

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-light text-secondary border small">{{ $product->brand->name ?? 'Chamunda' }}</span>
                                    <small class="text-warning">
                                        <i class="bi bi-star-fill"></i> {{ $product->average_rating }}
                                    </small>
                                </div>

                                <h6 class="fw-bold mb-1" style="height: 40px; overflow: hidden;" title="{{ $product->name }}">
                                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <small class="text-muted d-block mb-3">SKU: {{ $product->sku }}</small>

                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex align-items-baseline gap-2 mb-3">
                                        <span class="fs-5 fw-bold text-ce-primary">₹{{ number_format($product->effective_price, 2) }}</span>
                                        @if($product->sale_price)
                                            <span class="text-muted text-decoration-line-through small">₹{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    @if($product->in_stock)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-ce-primary w-100 fw-semibold">
                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100 disabled" disabled>Out of Stock</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                    <i class="bi bi-search text-muted display-3 mb-3"></i>
                    <h4 class="fw-bold">No Products Found</h4>
                    <p class="text-muted mb-4">We could not find any electrical items matching your search criteria.</p>
                    <div>
                        <a href="{{ route('shop.index') }}" class="btn btn-ce-primary px-4">Browse All Products</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
