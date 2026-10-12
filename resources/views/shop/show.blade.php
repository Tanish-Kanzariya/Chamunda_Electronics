@extends('layouts.app')

@section('title', $product->name . ' - Chamunda Electrics')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 28) }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
        <div class="row g-4 g-lg-5">
            <!-- Product Image -->
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-light rounded-4 border d-flex justify-content-center align-items-center" style="min-height: 380px;">
                    <img src="{{ asset($product->image ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 340px; object-fit: contain;">
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-ce-primary text-white">{{ $product->brand->name ?? 'Chamunda Electrics' }}</span>
                    <span class="badge bg-light text-secondary border">SKU: {{ $product->sku }}</span>
                    @if($product->in_stock)
                        <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i> In Stock ({{ $product->stock_quantity }} units)</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Out of Stock</span>
                    @endif
                </div>

                <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

                <!-- Rating -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="fw-bold">{{ $product->average_rating }}</span>
                    <span class="text-muted small">({{ $product->reviews_count }} verified ratings)</span>
                </div>

                <!-- Price Box -->
                <div class="p-3 bg-light rounded-3 mb-4 d-flex align-items-baseline gap-3">
                    <span class="display-6 fw-bold text-ce-primary">₹{{ number_format($product->effective_price, 2) }}</span>
                    @if($product->sale_price)
                        <span class="fs-5 text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-danger fw-bold">Save {{ $product->discount_percentage }}%</span>
                    @endif
                    <small class="text-muted ms-auto">Inclusive of all taxes</small>
                </div>

                <p class="text-secondary mb-4">
                    {{ $product->short_description }}
                </p>

                <!-- Add to Cart Form -->
                @if($product->in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-3 col-4">
                                <label class="form-label small fw-bold mb-1">Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control text-center fw-bold">
                            </div>
                            <div class="col-sm-5 col-8 pt-sm-4">
                                <button type="submit" class="btn btn-ce-primary w-100 py-2 fw-bold">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </button>
                            </div>
                            @auth
                                <div class="col-sm-4 pt-sm-4">
                                    <form action="{{ route('customer.wishlist.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold">
                                            <i class="bi bi-heart me-1"></i> Wishlist
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-info-circle me-1"></i> This item is currently out of stock. Contact our store team to reserve next dispatch.
                    </div>
                @endif

                <!-- Store Highlights -->
                <div class="row g-2 pt-3 border-top small text-secondary">
                    <div class="col-6 col-sm-4"><i class="bi bi-shield-check text-success me-1"></i> 100% Genuine ISI</div>
                    <div class="col-6 col-sm-4"><i class="bi bi-truck text-primary me-1"></i> Fast Doorstep Dispatch</div>
                    <div class="col-6 col-sm-4"><i class="bi bi-receipt text-warning me-1"></i> Official Tax GST Invoice</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs: Specifications, Description, Reviews -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
        <ul class="nav nav-tabs nav-fill mb-4" id="productTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button">
                    <i class="bi bi-list-check me-1"></i> Technical Specifications
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button">
                    <i class="bi bi-file-text me-1"></i> Product Overview
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button">
                    <i class="bi bi-star me-1"></i> Customer Reviews ({{ $product->reviews_count }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="productTabsContent">
            <!-- Specifications Tab -->
            <div class="tab-pane fade show active" id="specs-pane">
                @if(!empty($product->technical_specs) && is_array($product->technical_specs))
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <tbody>
                                @foreach($product->technical_specs as $key => $val)
                                    <tr>
                                        <th class="w-25 text-secondary">{{ $key }}</th>
                                        <td class="fw-semibold">{{ $val }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Standard manufacturer specifications apply. Contact Chamunda Electrics support for detailed product datasheets.</p>
                @endif
            </div>

            <!-- Description Tab -->
            <div class="tab-pane fade" id="desc-pane">
                <div class="lh-lg text-secondary">
                    {!! nl2br(e($product->description ?? $product->short_description)) !!}
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews-pane">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <h5 class="fw-bold mb-3">Verified Buyer Feedback</h5>
                        @forelse($product->reviews as $rev)
                            <div class="p-3 border rounded-3 mb-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark">{{ $rev->user->name ?? 'Customer' }}</div>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-1 text-secondary small">{{ $rev->comment }}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $rev->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="text-muted py-3">No reviews yet for this product. Be the first to share your experience!</div>
                        @endforelse
                    </div>

                    <!-- Add Review Form -->
                    <div class="col-lg-5">
                        <div class="p-4 bg-light rounded-4 border">
                            <h5 class="fw-bold mb-3">Leave a Review</h5>
                            @auth
                                <form action="{{ route('product.review', $product->slug) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Your Rating</label>
                                        <select name="rating" class="form-select form-select-sm" required>
                                            <option value="5">⭐⭐⭐⭐⭐ (5 - Outstanding)</option>
                                            <option value="4">⭐⭐⭐⭐ (4 - Very Good)</option>
                                            <option value="3">⭐⭐⭐ (3 - Good)</option>
                                            <option value="2">⭐⭐ (2 - Fair)</option>
                                            <option value="1">⭐ (1 - Poor)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Your Comment</label>
                                        <textarea name="comment" rows="3" class="form-control form-control-sm" placeholder="Tell us about the build quality, performance, or installation..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-ce-primary btn-sm w-100 fw-bold">Submit Review</button>
                                </form>
                            @else
                                <p class="text-muted small mb-3">Please sign in to submit a rating and product review.</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold">Login to Review</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mb-5">
            <h4 class="fw-bold mb-4">Related Electrical Products</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $rel)
                    <div class="col-6 col-md-3">
                        <div class="product-card p-3">
                            <a href="{{ route('product.show', $rel->slug) }}" class="d-block text-center mb-2">
                                <img src="{{ asset($rel->image ?? 'images/products/polycab-flamegard-wire.svg') }}" alt="{{ $rel->name }}" class="img-fluid" style="height: 140px; object-fit: contain;">
                            </a>
                            <h6 class="fw-bold mb-1 text-truncate" title="{{ $rel->name }}">
                                <a href="{{ route('product.show', $rel->slug) }}" class="text-decoration-none text-dark">{{ $rel->name }}</a>
                            </h6>
                            <div class="fw-bold text-ce-primary mt-auto">₹{{ number_format($rel->effective_price, 2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
