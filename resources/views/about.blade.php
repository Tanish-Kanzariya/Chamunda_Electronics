@extends('layouts.app')

@section('title', 'About Us - Chamunda Electrics')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" style="max-width: 700px; margin: 0 auto;">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Our Heritage</span>
        <h2 class="display-6 fw-bold">Powering Gujarat with Trust Since 1998</h2>
        <p class="text-muted lead">Over 25 years of delivering authentic electrical equipment, industrial switchgears, and energy-efficient lighting solutions.</p>
    </div>

    <div class="row g-4 align-items-center mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold text-ce-primary mb-3">About Chamunda Electrics</h3>
            <p class="text-secondary lh-lg mb-3">
                Founded with a mission to bring high-standard safety equipment and certified copper wiring to contractors, builders, and homeowners, <strong>Chamunda Electrics</strong> has grown into a premier authorized distributor for India's foremost electrical brands including Polycab, Havells, Anchor by Panasonic, Philips, Crompton, and Schneider Electric.
            </p>
            <p class="text-secondary lh-lg mb-4">
                Whether you require high-tensile submersible pump cables for agricultural applications, flame-retardant low-smoke wires for high-rise towers, or sleek modular switches for modern villas, our knowledgeable electrical team ensures you get the exact specifications, verified ISI certification, and direct trade prices.
            </p>
            <div class="row g-3 text-center">
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <h3 class="fw-bold text-ce-primary mb-0">25+</h3>
                        <small class="text-muted">Years Experience</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <h3 class="fw-bold text-ce-primary mb-0">5000+</h3>
                        <small class="text-muted">Active Products</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <h3 class="fw-bold text-ce-primary mb-0">15,000+</h3>
                        <small class="text-muted">Satisfied Clients</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 text-center">
            <img src="{{ asset('images/products/havells-stealth-air-bldc.svg') }}" alt="Chamunda Electrics" class="img-fluid rounded-4 shadow-sm border p-4 bg-white" style="max-height: 380px;">
        </div>
    </div>

    <!-- Pillars of Trust -->
    <div class="row g-4 pt-4 border-top">
        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                <i class="bi bi-shield-check text-success display-5 mb-3"></i>
                <h5 class="fw-bold">100% Genuine ISI Mark</h5>
                <p class="text-secondary small mb-0">
                    We source directly from manufacturer warehouses. Zero counterfeit items, zero compromises on conductor purity and safety standards.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                <i class="bi bi-receipt-cutoff text-primary display-5 mb-3"></i>
                <h5 class="fw-bold">Official GST Billing</h5>
                <p class="text-secondary small mb-0">
                    Full GST invoice with HSN codes provided for all residential and business purchases, making it easy for contractors to claim input tax credits.
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                <i class="bi bi-truck text-warning display-5 mb-3"></i>
                <h5 class="fw-bold">Site-Direct Dispatch</h5>
                <p class="text-secondary small mb-0">
                    Fast delivery directly to construction sites, workshops, or residential premises with careful packaging to prevent damage to fragile lighting and appliances.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
