<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Chamunda Electrics')</title>

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --ce-sidebar-bg: #0f172a;
            --ce-sidebar-hover: #1e293b;
            --ce-primary: #0f4c81;
            --ce-accent: #f59e0b;
        }
        body {
            background-color: #f1f5f9;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--ce-sidebar-bg);
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            margin: 0.15rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--ce-sidebar-hover);
        }
        .admin-sidebar .nav-link.active {
            border-left: 4px solid var(--ce-accent);
        }
        .stat-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="admin-sidebar d-flex flex-column flex-shrink-0 p-3">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 text-white text-decoration-none px-2">
                <i class="bi bi-lightning-charge-fill text-warning fs-3 me-2"></i>
                <div>
                    <div class="fw-bold fs-5 tracking-tight">CHAMUNDA</div>
                    <small class="text-secondary tracking-widest text-uppercase" style="font-size: 0.7rem;">Admin Control</small>
                </div>
            </a>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 fs-5"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam fs-5"></i> Orders
                        @php $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count(); @endphp
                        @if($pendingOrders > 0)
                            <span class="badge bg-warning text-dark ms-auto">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-cpu fs-5"></i> Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags fs-5"></i> Categories
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <i class="bi bi-award fs-5"></i> Brands
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="bi bi-percent fs-5"></i> Coupons
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="bi bi-people fs-5"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <i class="bi bi-envelope fs-5"></i> Inquiries
                        @php $unreadMsgs = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                        @if($unreadMsgs > 0)
                            <span class="badge bg-danger ms-auto">{{ $unreadMsgs }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <hr class="text-secondary">
            <div class="px-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-light w-100 mb-2">
                    <i class="bi bi-shop me-1"></i> View Live Store
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-grow-1 d-flex flex-column" style="min-width: 0;">
            <!-- Topbar -->
            <header class="navbar navbar-expand bg-white border-bottom px-4 py-3 sticky-top">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-secondary">Chamunda Electrics Control Center</span>
                </div>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="text-secondary small d-none d-md-inline">Logged in as <strong>{{ Auth::user()->name }}</strong> (Super Admin)</span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Storefront
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <main class="p-4 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
