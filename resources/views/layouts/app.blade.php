<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chamunda Electrics - Premium Electrical Goods & Industrial Supplies')</title>

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Swiper 11 Touch Slider CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Custom Theme Styling -->
    <style>
        :root {
            --ce-primary: #0f4c81;
            --ce-primary-hover: #0a355c;
            --ce-accent: #f59e0b;
            --ce-accent-hover: #d97706;
            --ce-dark: #1e293b;
            --ce-light: #f8fafc;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .bg-ce-primary { background-color: var(--ce-primary) !important; }
        .text-ce-primary { color: var(--ce-primary) !important; }
        .bg-ce-accent { background-color: var(--ce-accent) !important; }
        .text-ce-accent { color: var(--ce-accent) !important; }
        .btn-ce-primary {
            background-color: var(--ce-primary);
            border-color: var(--ce-primary);
            color: #ffffff;
            font-weight: 600;
        }
        .btn-ce-primary:hover, .btn-ce-primary:focus {
            background-color: var(--ce-primary-hover);
            border-color: var(--ce-primary-hover);
            color: #ffffff;
        }
        .btn-ce-accent {
            background-color: var(--ce-accent);
            border-color: var(--ce-accent);
            color: #1e293b;
            font-weight: 700;
        }
        .btn-ce-accent:hover, .btn-ce-accent:focus {
            background-color: var(--ce-accent-hover);
            border-color: var(--ce-accent-hover);
            color: #ffffff;
        }
        .product-card {
            transition: all 0.25s ease-in-out;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            background: #ffffff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px -6px rgba(15, 76, 129, 0.12);
            border-color: #cbd5e1;
        }
        .top-announcement {
            background-color: #0b2545;
            color: #cbd5e1;
            font-size: 0.825rem;
            padding: 6px 0;
        }
        .navbar-main {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            border-bottom: 1px solid #f1f5f9;
        }
        .navbar-categories {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
        .footer-dark {
            background-color: #0f172a;
            color: #94a3b8;
            margin-top: auto;
        }
        .footer-dark h5, .footer-dark h6 {
            color: #f8fafc;
        }
        .footer-dark a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.15s;
        }
        .footer-dark a:hover {
            color: var(--ce-accent);
        }
        .badge-discount {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 700;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- 1. Top Announcement Bar -->
    <div class="top-announcement">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-none d-md-flex align-items-center gap-3">
                <span><i class="bi bi-shield-check text-warning me-1"></i> 100% Genuine ISI Certified Products</span>
                <span><i class="bi bi-truck text-warning me-1"></i> Free Shipping on Orders over ₹999</span>
                <span><i class="bi bi-receipt text-warning me-1"></i> GST Invoices for Contractors & Businesses</span>
            </div>
            <div class="d-flex align-items-center ms-auto gap-3">
                <a href="tel:+919876543210" class="text-light text-decoration-none"><i class="bi bi-telephone-fill text-warning me-1"></i> +91 98765 43210</a>
                <span class="text-secondary">|</span>
                <a href="{{ route('contact') }}" class="text-light text-decoration-none"><i class="bi bi-geo-alt-fill text-warning me-1"></i> Store Locator</a>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-main sticky-top py-2">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Chamunda Electrics" height="46" class="me-2">
            </a>

            <!-- Search Bar -->
            <form action="{{ route('shop.index') }}" method="GET" class="d-none d-lg-flex flex-grow-1 mx-4" style="max-width: 550px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control border-end-0 py-2" placeholder="Search wires, switches, LED panels, MCBs, fans..." value="{{ request('q') }}">
                    <button class="btn btn-ce-primary px-4" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </form>

            <!-- Navigation Actions -->
            <div class="d-flex align-items-center gap-2">
                <!-- Mobile Search Trigger -->
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Wishlist -->
                @auth
                <a href="{{ route('customer.wishlist') }}" class="btn btn-light position-relative p-2" title="Wishlist">
                    <i class="bi bi-heart fs-5 text-danger"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="wishlistCountBadge">
                        {{ \App\Models\Wishlist::where('user_id', Auth::id())->count() }}
                    </span>
                </a>
                @endauth

                <!-- Cart -->
                @php
                    $cartItems = session()->get('cart', []);
                    $cartTotalCount = array_sum(array_column($cartItems, 'quantity'));
                @endphp
                <a href="{{ route('cart.index') }}" class="btn btn-light position-relative p-2" title="View Cart">
                    <i class="bi bi-cart3 fs-5 text-ce-primary"></i>
                    @if($cartTotalCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fw-bold">
                            {{ $cartTotalCount }}
                        </span>
                    @endif
                </a>

                <!-- Admin Direct Access Button -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm text-dark">
                            <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                        </a>
                    @endif
                @endauth

                <!-- User Account Dropdown -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 text-ce-primary"></i>
                            <span class="d-none d-sm-inline fw-semibold">{{ Str::limit(Auth::user()->name, 12) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li class="px-3 py-1 text-muted small">Signed in as <strong>{{ Auth::user()->role === 'admin' ? 'Admin' : 'Customer' }}</strong></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item fw-semibold text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Admin Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid me-2"></i> My Account</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="bi bi-box-seam me-2"></i> My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.wishlist') }}"><i class="bi bi-heart me-2"></i> Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="bi bi-gear me-2"></i> Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary fw-semibold px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-ce-accent fw-bold px-3 d-none d-sm-inline">
                        Register
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Search Dropdown -->
        <div class="collapse d-lg-none w-100 mt-2 px-3" id="mobileSearch">
            <form action="{{ route('shop.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search electrical supplies..." value="{{ request('q') }}">
                    <button class="btn btn-ce-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </nav>

    <!-- 3. Category Header Bar -->
    <div class="navbar-categories d-none d-md-block py-2">
        <div class="container">
            <ul class="nav nav-pills align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link fw-bold text-dark px-2 {{ request()->routeIs('shop.index') && !request('category') ? 'active bg-ce-primary text-white' : '' }}" href="{{ route('shop.index') }}">
                        <i class="bi bi-grid-fill me-1"></i> All Products
                    </a>
                </li>
                @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(6)->get() as $cat)
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 text-secondary text-nowrap rounded-pill {{ request('category') === $cat->slug ? 'active bg-ce-primary text-white' : '' }}" href="{{ route('shop.index', ['category' => $cat->slug]) }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
                <li class="nav-item ms-auto">
                    <a class="nav-link py-1 px-3 text-danger fw-bold text-nowrap" href="{{ route('shop.index', ['sort' => 'price_low']) }}">
                        <i class="bi bi-percent me-1"></i> Hot Deals
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 4. Flash Notifications -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-shield-slash me-1"></i> Please check the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- 5. Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- 6. Trust Badges Strip -->
    <section class="py-4 bg-white border-top mt-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-patch-check-fill text-ce-primary fs-1"></i>
                        <div class="text-start">
                            <h6 class="mb-0 fw-bold">100% Genuine</h6>
                            <small class="text-muted">ISI & ISO Certified</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-truck text-warning fs-1"></i>
                        <div class="text-start">
                            <h6 class="mb-0 fw-bold">Express Delivery</h6>
                            <small class="text-muted">Fast & safe dispatch</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-shield-lock-fill text-success fs-1"></i>
                        <div class="text-start">
                            <h6 class="mb-0 fw-bold">Secure Payments</h6>
                            <small class="text-muted">COD & UPI Supported</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-headset text-danger fs-1"></i>
                        <div class="text-start">
                            <h6 class="mb-0 fw-bold">Electrician Support</h6>
                            <small class="text-muted">Technical consultation</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Footer -->
    <footer class="footer-dark pt-5 pb-3">
        <div class="container">
            <div class="row g-4 pb-4 border-bottom border-secondary">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo.svg') }}" alt="Chamunda Electrics" height="42" class="bg-white p-1 rounded">
                    </div>
                    <p class="small text-secondary mb-3">
                        Chamunda Electrics is your one-stop supplier for premium residential, commercial, and industrial electrical equipment, cables, modular switchgears, and energy-saving lighting.
                    </p>
                    <div class="small text-secondary">
                        <p class="mb-1"><i class="bi bi-geo-alt me-2 text-warning"></i> Shop No. 12-14, Shree Chamunda Complex, Station Road, Gujarat</p>
                        <p class="mb-1"><i class="bi bi-telephone me-2 text-warning"></i> +91 98765 43210 / +91 98250 12345</p>
                        <p class="mb-0"><i class="bi bi-envelope me-2 text-warning"></i> support@chamundaelectrics.com</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="text-white text-uppercase fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index') }}">Product Catalog</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}">About Our Store</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li class="mb-2"><a href="{{ route('cart.index') }}">Shopping Cart</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="text-white text-uppercase fw-bold mb-3">Top Categories</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'wires-cables']) }}">Wires & Cables</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'switches-sockets']) }}">Modular Switches</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'lighting-led']) }}">LED & Panel Lights</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'fans-cooling']) }}">BLDC Ceiling Fans</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'switchgears-mcbs']) }}">MCBs & Distribution</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white text-uppercase fw-bold mb-3">Electrician & Contractor Help</h6>
                    <p class="small text-secondary mb-3">
                        Need wholesale quotation for bulk building projects or farm submersible installations? Talk to our electrical engineers.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-ce-accent w-100 fw-bold">Request Bulk Quote</a>
                    <div class="mt-3 text-secondary small">
                        <span class="badge bg-secondary me-1">GSTIN Verified</span>
                        <span class="badge bg-secondary">Authorized Dealer</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 small text-secondary">
                <div>&copy; {{ date('Y') }} Chamunda Electrics. All rights reserved. Designed for excellence.</div>
                <div class="mt-2 mt-md-0">
                    <span class="me-3">Payment methods: Cash on Delivery | UPI | NetBanking</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper 11 Touch Slider JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
