@extends('layouts.admin')

@section('title', 'Add New Product - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Add New Electrical Product</h3>
        <small class="text-secondary">Create a new product listing with technical specs, stock, and pricing.</small>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Catalog
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">General Product Information</h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Polycab Flamegard 1.5 sq mm Wire (90m Red)" value="{{ old('name') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">SKU Code <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control text-uppercase" placeholder="e.g. PC-FG-15-RED" value="{{ old('sku') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="stock_quantity" class="form-control" min="0" value="{{ old('stock_quantity', 50) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Short Summary / Highlights</label>
                    <input type="text" name="short_description" class="form-control" placeholder="Key 1-line selling point (e.g. FR-LSH single core multi-strand copper wire)" value="{{ old('short_description') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Product Description</label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Detailed product overview, applications, conductor materials, safety benefits...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Technical Specs Builder -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0">Technical Specifications</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="addSpecRow()">
                        <i class="bi bi-plus-circle me-1"></i> Add Spec Row
                    </button>
                </div>
                <small class="text-muted d-block mb-3">Add electrical parameters like Voltage Rating, Wattage, Conductor Material, Warranty, BEE Star, ISI Standard, etc.</small>

                <div id="specsContainer">
                    <div class="row g-2 mb-2 spec-row">
                        <div class="col-5">
                            <input type="text" name="specs_keys[]" class="form-control form-control-sm" placeholder="Parameter (e.g. Voltage)">
                        </div>
                        <div class="col-6">
                            <input type="text" name="specs_values[]" class="form-control form-control-sm" placeholder="Value (e.g. 1100 Volts)">
                        </div>
                        <div class="col-1 text-center">
                            <button type="button" class="btn btn-sm text-danger" onclick="this.closest('.spec-row').remove()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="row g-2 mb-2 spec-row">
                        <div class="col-5">
                            <input type="text" name="specs_keys[]" class="form-control form-control-sm" placeholder="Parameter (e.g. Certification)" value="Certification">
                        </div>
                        <div class="col-6">
                            <input type="text" name="specs_values[]" class="form-control form-control-sm" placeholder="Value" value="IS 694 / ISI Certified">
                        </div>
                        <div class="col-1 text-center">
                            <button type="button" class="btn btn-sm text-danger" onclick="this.closest('.spec-row').remove()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="row g-2 mb-2 spec-row">
                        <div class="col-5">
                            <input type="text" name="specs_keys[]" class="form-control form-control-sm" placeholder="Parameter (e.g. Warranty)" value="Warranty">
                        </div>
                        <div class="col-6">
                            <input type="text" name="specs_values[]" class="form-control form-control-sm" placeholder="Value" value="2 Years Manufacturer Warranty">
                        </div>
                        <div class="col-1 text-center">
                            <button type="button" class="btn btn-sm text-danger" onclick="this.closest('.spec-row').remove()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-lg-4">
            <!-- Organization -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">Organization</h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Brand</label>
                    <select name="brand_id" class="form-select">
                        <option value="">None / Generic</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">Pricing (₹)</h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Regular Price (MRP) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 1850.00" value="{{ old('price') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Discounted Sale Price (Optional)</label>
                    <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="e.g. 1499.00" value="{{ old('sale_price') }}">
                    <small class="text-muted">Leave empty if no discount.</small>
                </div>
            </div>

            <!-- Image & Visibility -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">Media & Status</h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Product Image</label>
                    <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted">If omitted, an authentic vector electrical illustration is assigned.</small>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="is_featured">Feature on Storefront Homepage</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label small fw-bold" for="is_active">Active & Available for Sale</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                    <i class="bi bi-save me-1"></i> Save Product Listing
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function addSpecRow() {
    const container = document.getElementById('specsContainer');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 spec-row';
    row.innerHTML = `
        <div class="col-5">
            <input type="text" name="specs_keys[]" class="form-control form-control-sm" placeholder="Parameter (e.g. Color)">
        </div>
        <div class="col-6">
            <input type="text" name="specs_values[]" class="form-control form-control-sm" placeholder="Value (e.g. Matte Black)">
        </div>
        <div class="col-1 text-center">
            <button type="button" class="btn btn-sm text-danger" onclick="this.closest('.spec-row').remove()"><i class="bi bi-trash"></i></button>
        </div>
    `;
    container.appendChild(row);
}
</script>
@endsection
