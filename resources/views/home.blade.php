@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="container hero">
        <div class="hero-content">
            <h1>Aroma Hangat & Kelembutan di Setiap <span>Gigitan</span></h1>
            <p>SweetCrust Bakery menyajikan roti panggang segar dan pastri Prancis premium yang dibuat secara handmade setiap hari dengan bahan-bahan organik pilihan terbaik.</p>
            <div class="hero-buttons">
                <a href="{{ route('catalog') }}" class="btn btn-primary"><i class="fa-solid fa-cookie-bite"></i> Lihat Menu Kami</a>
                <a href="#kategori" class="btn btn-secondary">Jelajahi Kategori</a>
            </div>
        </div>
        <div class="hero-image-wrapper">
            <img src="{{ asset('images/products/croissant.png') }}" alt="Croissant SweetCrust" class="hero-image">
            <div class="hero-badge">
                <span class="hero-badge-icon">🥐</span>
                <div class="hero-badge-text">
                    <h5>Freshly Baked Daily</h5>
                    <p>Dipanggang jam 05:00 WIB</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="kategori" class="categories-section" style="background-color: white; border-top: 1px solid var(--surface-border); border-bottom: 1px solid var(--surface-border);">
        <div class="container">
            <div class="section-header">
                <h2>Jelajahi Berdasarkan Kategori</h2>
                <p>Temukan favorit Anda dari koleksi roti, pastri, kue kering, dan cake ulang tahun terbaik kami.</p>
            </div>
            
            <div class="category-grid">
                <a href="{{ route('catalog', ['category' => 'breads']) }}" class="category-card">
                    <span class="category-icon">🍞</span>
                    <h3>Roti Klasik</h3>
                    <p>Sourdough alami, gandum utuh, & soft bread</p>
                </a>
                <a href="{{ route('catalog', ['category' => 'pastries']) }}" class="category-card">
                    <span class="category-icon">🥐</span>
                    <h3>Pastri Prancis</h3>
                    <p>Croissant flaky berongga & danish buah segar</p>
                </a>
                <a href="{{ route('catalog', ['category' => 'cakes']) }}" class="category-card">
                    <span class="category-icon">🎂</span>
                    <h3>Premium Cakes</h3>
                    <p>Shortcake stroberi segar & cake ulang tahun custom</p>
                </a>
                <a href="{{ route('catalog', ['category' => 'cookies']) }}" class="category-card">
                    <span class="category-icon">🍪</span>
                    <h3>Kue Kering</h3>
                    <p>Cookies NY-Style kenyal manis dengan sea salt</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2>Rekomendasi Chef Hari Ini</h2>
                <p>Nikmati menu andalan SweetCrust yang paling diminati dan paling cepat habis dipesan.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="product-grid">
                @foreach($featuredProducts as $product)
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <span class="product-badge">{{ ucfirst($product->category) }}</span>
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                        </div>
                        <div class="product-body">
                            <span class="product-cat">
                                @if($product->category == 'breads') Roti
                                @elseif($product->category == 'cakes') Cake
                                @elseif($product->category == 'pastries') Pastri
                                @else Cookies
                                @endif
                            </span>
                            <a href="{{ route('detail', $product->slug) }}" class="product-title">{{ $product->name }}</a>
                            <p class="product-desc">{{ $product->description }}</p>
                            
                            <div class="product-footer">
                                <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-to-cart-btn" title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('catalog') }}" class="btn btn-outline">Lihat Seluruh Menu <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Value Proposition Section -->
    <section class="categories-section" style="background-color: var(--secondary); color: white;">
        <div class="container">
            <div class="section-header">
                <h2 style="color: white;">Mengapa Memilih SweetCrust?</h2>
                <p style="color: #B5A89E;">Kami menjaga tradisi bakeri artisan klasik dengan sentuhan bahan modern.</p>
            </div>
            
            <div class="category-grid">
                <div class="category-card" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    <span class="category-icon">🌾</span>
                    <h3 style="color: white; margin-top: 10px;">100% Gandum Organik</h3>
                    <p style="color: #B5A89E;">Tepung gandum utuh organik berkualitas tinggi tanpa bahan pemutih atau pengawet kimia.</p>
                </div>
                <div class="category-card" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    <span class="category-icon">🧈</span>
                    <h3 style="color: white; margin-top: 10px;">Mentega Prancis AOP</h3>
                    <p style="color: #B5A89E;">Menggunakan mentega berlabel AOP resmi asal Prancis untuk aroma karamel gurih alami yang mewah.</p>
                </div>
                <div class="category-card" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    <span class="category-icon">⏱️</span>
                    <h3 style="color: white; margin-top: 10px;">24 Jam Fermentasi</h3>
                    <p style="color: #B5A89E;">Adonan roti difermentasi lambat selama 24 jam penuh untuk tekstur kenyal sempurna dan ramah pencernaan.</p>
                </div>
                <div class="category-card" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    <span class="category-icon">🛵</span>
                    <h3 style="color: white; margin-top: 10px;">Pesan Antar Segar</h3>
                    <p style="color: #B5A89E;">Dikirim dalam keadaan segar langsung dari oven pemanggang ke pintu rumah Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="products-section" style="background-color: var(--bg);">
        <div class="container">
            <div class="section-header">
                <h2>Kata Pelanggan Kami</h2>
                <p>Kepuasan Anda adalah kebahagiaan terbesar kami.</p>
            </div>
            
            <div class="category-grid">
                <div class="glass-card" style="padding: 24px;">
                    <div style="color: var(--primary); margin-bottom: 12px;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p style="font-style: italic; font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 15px;">"Chocolate Croissant-nya luar biasa! Kulitnya renyah berlapis-lapis dan cokelat Belgia-nya meleleh saat digigit. Favorit saya untuk sarapan pagi!"</p>
                    <strong style="font-size: 15px; color: var(--secondary);">— Amanda S., Food Vlogger</strong>
                </div>
                <div class="glass-card" style="padding: 24px;">
                    <div style="color: var(--primary); margin-bottom: 12px;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p style="font-style: italic; font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 15px;">"Sebagai penggemar sourdough, classic sourdough di sini terbaik di Jakarta. Kulitnya renyah, aromanya gurih asam alami, dan kenyal di dalam."</p>
                    <strong style="font-size: 15px; color: var(--secondary);">— Budi H., Pembuat Roti Rumahan</strong>
                </div>
                <div class="glass-card" style="padding: 24px;">
                    <div style="color: var(--primary); margin-bottom: 12px;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p style="font-style: italic; font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 15px;">"Membeli Strawberry Shortcake untuk ulang tahun ibu saya. Kuenya lembut sekali, krimnya ringan dan tidak terlalu manis. Stroberinya berlimpah!"</p>
                    <strong style="font-size: 15px; color: var(--secondary);">— Rian D., Customer Setia</strong>
                </div>
                <div class="glass-card" style="padding: 24px;">
                    <div style="color: var(--primary); margin-bottom: 12px;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p style="font-style: italic; font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 15px;">"Cookies NY-Style-nya sangat lezat. Bagian tengahnya chewy dengan sea salt flakes yang menambah keseimbangan rasa manis gurih cokelatnya."</p>
                    <strong style="font-size: 15px; color: var(--secondary);">— Clarissa K., Karyawan Swasta</strong>
                </div>
            </div>
        </div>
    </section>
@endsection
