@extends('layouts.admin')

@section('title', 'Manage Brands - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Electrical Brands</h3>
        <small class="text-secondary">Manage official manufacturers and authorized supply brands.</small>
    </div>
    <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#createBrandModal">
        <i class="bi bi-plus-circle me-1"></i> Add Brand
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Brand</th>
                    <th>Slug</th>
                    <th>Products Count</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset($brand->logo ?? 'images/brands/polycab.svg') }}" width="60" height="30" class="rounded border p-1 bg-light object-fit-contain">
                                <div>
                                    <div class="fw-bold text-dark">{{ $brand->name }}</div>
                                    <small class="text-muted">{{ Str::limit($brand->description, 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $brand->slug }}</code></td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $brand->products_count }} products</span></td>
                        <td>
                            @if($brand->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBrandModal_{{ $brand->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Delete brand?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editBrandModal_{{ $brand->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Brand: {{ $brand->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Brand Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Description</label>
                                            <textarea name="description" rows="3" class="form-control">{{ $brand->description }}</textarea>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="brandActive_{{ $brand->id }}" {{ $brand->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold" for="brandActive_{{ $brand->id }}">Active</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createBrandModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.brands.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Panasonic / V-Guard" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Manufacturer description..."></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="newBrandActive" checked>
                        <label class="form-check-label small fw-bold" for="newBrandActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Create Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
