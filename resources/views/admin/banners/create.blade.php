@extends('layouts.admin')

@section('title', 'Add New Hero Banner')
@section('header', 'Add Hero Banner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Upload New Hero Banner</h4>
                <p class="text-muted small mb-0">Create a promotional banner to display in the main storefront carousel.</p>
            </div>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Banners
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section: Banner Image -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Banner Graphic / Image <span class="text-danger">*</span></label>
                    <div class="p-3 border-2 border-dashed rounded-4 bg-light text-center mb-3 position-relative" style="border: 2px dashed #cbd5e1;">
                        <div id="imagePreviewContainer" class="d-none mb-3">
                            <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: cover;">
                        </div>
                        <i class="bi bi-cloud-arrow-up display-5 text-secondary mb-2 d-block" id="uploadIcon"></i>
                        <p class="mb-2 fw-semibold text-dark">Click to select an image from your computer</p>
                        <p class="text-muted small mb-3">Recommended resolution: <strong>1200 × 480 px</strong> (JPG, PNG, WEBP, or SVG, Max 5MB)</p>
                        <input type="file" name="image" id="bannerImageInput" class="form-control" accept="image/*" onchange="previewBannerImage(this)">
                    </div>

                    <div class="text-muted small">
                        <strong>Or use an internal/web image path:</strong>
                        <input type="text" name="image_url_input" class="form-control form-control-sm mt-1" placeholder="e.g. images/banners/banner-mega-sale.svg or https://example.com/banner.jpg" value="{{ old('image_url_input') }}">
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Section: Content & Headings (Optional) -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Promotional Badge Text</label>
                        <input type="text" name="badge_text" class="form-control" placeholder="e.g. FESTIVE MEGA SALE" value="{{ old('badge_text') }}">
                        <small class="text-muted">Top golden tag on the banner</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold">Banner Headline / Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Powering Your Projects with 100% Genuine Supplies" value="{{ old('title') }}">
                        <small class="text-muted">Main headline text</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Subtitle / Description</label>
                        <textarea name="subtitle" class="form-control" rows="2" placeholder="e.g. Polycab Wires, Havells Switches, Philips LED & BLDC Fans with GST Invoice & Express Dispatch.">{{ old('subtitle') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Target Link URL</label>
                        <input type="text" name="link_url" class="form-control" placeholder="e.g. /shop or /category/wires-cables" value="{{ old('link_url', '/shop') }}">
                        <small class="text-muted">Where customers go when they click this banner</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Button CTA Text</label>
                        <input type="text" name="button_text" class="form-control" placeholder="e.g. Shop Now" value="{{ old('button_text', 'Shop Now') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Display Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 1) }}" min="0">
                        <small class="text-muted">Lower numbers show first (0, 1, 2...)</small>
                    </div>
                </div>

                <div class="form-check form-switch mt-4 p-3 bg-light rounded-3">
                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="isActive" value="1" checked style="transform: scale(1.3);">
                    <label class="form-check-label fw-bold text-dark" for="isActive">
                        Make this banner Active immediately on the front store
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-ce-primary px-5 fw-bold">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save & Publish Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewBannerImage(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const uploadIcon = document.getElementById('uploadIcon');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('d-none');
            uploadIcon.classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
