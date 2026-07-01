<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SweetCrust Bakery</title>
    
    <!-- Link to custom vanilla styling -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('home') }}" class="logo">
                <i class="fa-solid fa-wheat-awn"></i> Sweet<span>Crust</span>
            </a>
            
            <nav style="margin-top: 20px;">
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') || request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-cookie-bite"></i> Kelola Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') || request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-receipt"></i> Kelola Pesanan
                        </a>
                    </li>
                    <li style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                            <i class="fa-solid fa-globe"></i> Lihat Toko
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="sidebar-link" style="color: var(--error);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div style="margin-top: auto; font-size: 12px; color: #8C7C71; text-align: center;">
                SweetCrust Admin v1.0
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
