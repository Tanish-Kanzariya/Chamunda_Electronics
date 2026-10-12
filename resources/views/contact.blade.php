@extends('layouts.app')

@section('title', 'Contact Us - Chamunda Electrics')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" style="max-width: 700px; margin: 0 auto;">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Get In Touch</span>
        <h2 class="display-6 fw-bold">Visit Our Showroom or Send an Inquiry</h2>
        <p class="text-muted lead">Our experienced electrical staff is ready to help you with technical product sizing, contractor quotes, and delivery questions.</p>
    </div>

    <div class="row g-4">
        <!-- Contact Info Cards -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">Showroom Location & Contact</h5>

                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="bg-primary-subtle text-primary p-2 rounded-3">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Retail & Wholesale Showroom</h6>
                        <small class="text-secondary">Shop No. 12-14, Shree Chamunda Complex, Near Railway Station Road, Gujarat 380001</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="bg-success-subtle text-success p-2 rounded-3">
                        <i class="bi bi-telephone-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Phone / WhatsApp Helpline</h6>
                        <small class="text-secondary">+91 98765 43210 / +91 98250 12345</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="bg-warning-subtle text-warning p-2 rounded-3">
                        <i class="bi bi-envelope-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Email Support</h6>
                        <small class="text-secondary">support@chamundaelectrics.com</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-info-subtle text-info p-2 rounded-3">
                        <i class="bi bi-clock-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Working Hours</h6>
                        <small class="text-secondary">Monday - Saturday: 9:00 AM - 8:00 PM<br>Sunday: 10:00 AM - 2:00 PM</small>
                    </div>
                </div>
            </div>

            <!-- Contractor Bulk Inquiries note -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border">
                <h6 class="fw-bold text-ce-primary"><i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Contractor Bulk BOQ Sizing</h6>
                <p class="small text-secondary mb-0">
                    Send your Bill of Quantities (BOQ) or architectural wiring plans to our email or submit through the form for immediate bulk wholesale discount estimates.
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h4 class="fw-bold mb-3">Send Us a Message</h4>
                <p class="text-muted small mb-4">Fill out the form below and an electrical consultant will respond to you within 24 hours.</p>

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('phone', Auth::user()->phone ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Subject / Product Inquired <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Bulk quote for Polycab 2.5 sq mm wire or Geyser enquiry" value="{{ old('subject') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Message / Requirements <span class="text-danger">*</span></label>
                            <textarea name="message" rows="4" class="form-control" placeholder="Please describe what quantities or electrical specifications you need..." required>{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ce-primary btn-lg w-100 fw-bold shadow-sm mt-4">
                        Send Inquiry <i class="bi bi-send-fill ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
