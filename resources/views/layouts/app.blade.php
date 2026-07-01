<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SweetCrust Bakery') - Kelezatan Autentik Premium</title>
    
    <!-- Link to custom vanilla styling -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="container navbar">
            <a href="{{ route('home') }}" class="logo">
                <i class="fa-solid fa-wheat-awn"></i> Sweet<span>Crust</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') || request()->routeIs('detail') ? 'active' : '' }}">Menu Showcase</a></li>
                <li>
                    <!-- Easy order tracking -->
                    <form action="{{ route('track') }}" method="GET" style="display: inline-flex; align-items: center; gap: 8px;">
                        <input type="text" name="order_code" placeholder="Lacak Pesanan (e.g. SCB-...)" required 
                               style="padding: 6px 12px; border: 1px solid var(--surface-border); border-radius: var(--radius-full); font-size: 13px; outline: none; background: white; font-family: inherit;">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; height: auto;">Lacak</button>
                    </form>
                </li>
            </ul>

            <div class="nav-actions">
                <a href="{{ route('cart.index') }}" class="cart-icon" id="cart-icon-btn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    @php $cartCount = collect(session()->get('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                    <i class="fa-solid fa-lock"></i> Admin Panel
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="{{ route('home') }}" class="logo" style="color: white;">
                        <i class="fa-solid fa-wheat-awn"></i> Sweet<span>Crust</span>
                    </a>
                    <p style="margin-top: 15px;">Membawa kehangatan dan kebahagiaan ke meja makan Anda melalui produk bakery panggang segar berkualitas premium yang dibuat dengan bahan organik terbaik.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Kategori Menu</h4>
                    <ul>
                        <li><a href="{{ route('catalog', ['category' => 'breads']) }}">Roti Klasik</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'cakes']) }}">Kue Ulang Tahun</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'pastries']) }}">Pastri Prancis</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'cookies']) }}">Cookies Renyah</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Kontak Kami</h4>
                    <ul style="color: #B5A89E;">
                        <li><i class="fa-solid fa-location-dot" style="margin-right: 8px; color: var(--primary);"></i> Jl. Bakeri Indah No. 45, Jakarta</li>
                        <li><i class="fa-solid fa-phone" style="margin-right: 8px; color: var(--primary);"></i> +62 812-3456-7890</li>
                        <li><i class="fa-solid fa-envelope" style="margin-right: 8px; color: var(--primary);"></i> info@sweetcrust.com</li>
                        <li><i class="fa-solid fa-clock" style="margin-right: 8px; color: var(--primary);"></i> Setiap Hari: 07.00 - 21.00</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SweetCrust Bakery. Hak Cipta Dilindungi.</p>
                <div style="display: flex; gap: 16px;">
                    <a href="#" style="color: inherit;"><i class="fa-brands fa-instagram fa-lg"></i></a>
                    <a href="#" style="color: inherit;"><i class="fa-brands fa-facebook fa-lg"></i></a>
                    <a href="#" style="color: inherit;"><i class="fa-brands fa-whatsapp fa-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
