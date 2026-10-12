@extends('layouts.admin')

@section('title', 'Manage Products - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Electrical Products Catalog</h3>
        <small class="text-secondary">View, filter, edit prices, stock, and add electrical inventory items.</small>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Add New Product
    </a>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search by product name, SKU..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100 fw-semibold">Filter</button>
            @if(request()->hasAny(['search', 'category_id']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th style="min-width: 250px;">Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price (₹)</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset($product->image ?? 'images/products/polycab-flamegard-wire.svg') }}" width="48" height="48" class="rounded border p-1 bg-light object-fit-contain">
                                <div>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 260px;" title="{{ $product->name }}">{{ $product->name }}</div>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $product->category->name ?? 'None' }}</span></td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>
                            <div class="fw-bold text-primary">₹{{ number_format($product->effective_price, 2) }}</div>
                            @if($product->sale_price)
                                <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($product->stock_quantity <= 10)
                                <span class="badge bg-danger">{{ $product->stock_quantity }} Low</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock_quantity }} In Stock</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Preview on store">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Product">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No products found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
