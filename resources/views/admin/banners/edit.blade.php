@extends('layouts.admin')

@section('title', 'Edit Hero Banner')
@section('header', 'Edit Hero Banner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Edit Hero Banner</h4>
                <p class="text-muted small mb-0">Modify banner graphics, links, or ordering.</p>
            </div>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Banners
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Current Banner Preview -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Current Banner Preview</label>
                    <div class="p-3 bg-dark rounded-4 text-center mb-3">
                        <img id="currentImagePreview" src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: cover;">
                    </div>
                </div>

                <!-- Section: Change Banner Image -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Replace Banner Image (Optional)</label>
                    <div class="p-3 border-2 border-dashed rounded-4 bg-light text-center mb-3 position-relative" style="border: 2px dashed #cbd5e1;">
                        <div id="newImagePreviewContainer" class="d-none mb-3">
                            <span class="badge bg-primary mb-2">New Image Selected</span>
                            <img id="newImagePreview" src="#" alt="New Preview" class="img-fluid rounded-3 shadow-sm d-block mx-auto" style="max-height: 220px; object-fit: cover;">
                        </div>
                        <i class="bi bi-cloud-arrow-up display-5 text-secondary mb-2 d-block" id="uploadIcon"></i>
                        <p class="mb-2 fw-semibold text-dark">Click to select a new image to replace current one</p>
                        <p class="text-muted small mb-3">Recommended resolution: <strong>1200 × 480 px</strong> (JPG, PNG, WEBP, or SVG, Max 5MB)</p>
                        <input type="file" name="image" id="bannerImageInput" class="form-control" accept="image/*" onchange="previewNewBannerImage(this)">
                    </div>

                    <div class="text-muted small">
                        <strong>Or enter an image path / URL:</strong>
                        <input type="text" name="image_url_input" class="form-control form-control-sm mt-1" value="{{ old('image_url_input', $banner->image_path) }}" placeholder="e.g. images/banners/custom.svg">
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Section: Content & Headings -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Promotional Badge Text</label>
                        <input type="text" name="badge_text" class="form-control" placeholder="e.g. FESTIVE MEGA SALE" value="{{ old('badge_text', $banner->badge_text) }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold">Banner Headline / Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Powering Your Projects with 100% Genuine Supplies" value="{{ old('title', $banner->title) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Subtitle / Description</label>
                        <textarea name="subtitle" class="form-control" rows="2">{{ old('subtitle', $banner->subtitle) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Target Link URL</label>
                        <input type="text" name="link_url" class="form-control" placeholder="e.g. /shop or /category/wires-cables" value="{{ old('link_url', $banner->link_url) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Button CTA Text</label>
                        <input type="text" name="button_text" class="form-control" placeholder="e.g. Shop Now" value="{{ old('button_text', $banner->button_text) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Display Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                    </div>
                </div>

                <div class="form-check form-switch mt-4 p-3 bg-light rounded-3">
                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="isActive" value="1" {{ $banner->is_active ? 'checked' : '' }} style="transform: scale(1.3);">
                    <label class="form-check-label fw-bold text-dark" for="isActive">
                        Banner is Active on the storefront carousel
                    </label>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBannerModal">
                        <i class="bi bi-trash me-1"></i> Delete Banner
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-ce-primary px-5 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteBannerModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Delete Banner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            Are you sure you want to delete this banner permanently?
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Confirm Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewNewBannerImage(input) {
    const previewContainer = document.getElementById('newImagePreviewContainer');
    const preview = document.getElementById('newImagePreview');
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
