@extends('layouts.admin')

@section('title', 'Manage Hero Sliders')
@section('header', 'Hero Sliders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Storefront Hero Sliders</h4>
        <p class="text-muted small mb-0">Customize and manage interactive promotional slides displayed in the storefront hero slider.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Live Store
        </a>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-ce-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Slide
        </a>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
    <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
    <div>
        <strong>Recommended Slide Dimensions:</strong> For the best visual experience on the slider, upload images sized <strong>1200 × 480 px</strong> (wide landscape). Supported formats: JPG, PNG, WEBP, or SVG.
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        @if($banners->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 240px;">Banner Preview</th>
                            <th>Title & Details</th>
                            <th>Destination Link</th>
                            <th class="text-center">Sort Order</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>
                                    <div class="rounded-3 overflow-hidden border shadow-sm" style="max-width: 220px; max-height: 95px; background: #0f172a;">
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="img-fluid w-100" style="height: 85px; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    @if($banner->badge_text)
                                        <span class="badge bg-warning text-dark mb-1 fw-bold">{{ $banner->badge_text }}</span>
                                    @endif
                                    <div class="fw-bold text-dark">{{ $banner->title ?: 'Graphic Promotional Banner' }}</div>
                                    @if($banner->subtitle)
                                        <small class="text-muted d-block" style="max-width: 320px;">{{ Str::limit($banner->subtitle, 65) }}</small>
                                    @endif
                                    <small class="text-secondary">Image: <code>{{ Str::limit(basename($banner->image_path), 30) }}</code></small>
                                </td>
                                <td>
                                    <a href="{{ $banner->link_url ?: route('shop.index') }}" target="_blank" class="text-decoration-none small text-ce-primary fw-semibold">
                                        <i class="bi bi-link-45deg me-1"></i>{{ $banner->link_url ?: '/shop' }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $banner->sort_order }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3" title="Click to toggle status">
                                            <i class="bi {{ $banner->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle' }} me-1"></i>
                                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Banner">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBannerModal{{ $banner->id }}" title="Delete Banner">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade text-start" id="deleteBannerModal{{ $banner->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Delete Banner</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body py-3">
                                                    Are you sure you want to delete this banner:
                                                    <div class="my-2 p-2 bg-light rounded border text-dark fw-bold">
                                                        {{ $banner->title ?: 'Banner #' . $banner->id }}
                                                    </div>
                                                    This banner will be removed from your storefront immediately.
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-images display-4 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold">No Hero Banners Found</h5>
                <p class="text-muted">You haven't uploaded any banners yet. Create your first banner to showcase special offers.</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-ce-primary mt-2">
                    <i class="bi bi-plus-circle me-1"></i> Add First Banner
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
