@extends('layouts.app')

@section('title', 'Katalog Menu Showcase')

@section('content')
<div class="container">
    <div class="catalog-layout">
        
        <!-- Sidebar Filters -->
        <aside class="filter-sidebar glass-card">
            <form action="{{ route('catalog') }}" method="GET" id="filter-form">
                
                <!-- Search Input inside filter -->
                <div class="filter-group">
                    <h3 class="filter-title">Cari Menu</h3>
                    <div class="search-input-wrapper">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/deskripsi..." class="input-field">
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="filter-group">
                    <h3 class="filter-title">Kategori</h3>
                    <label class="filter-option">
                        <input type="radio" name="category" value="all" {{ !request('category') || request('category') == 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                        Semua Kategori
                    </label>
                    @foreach($categories as $key => $name)
                        <label class="filter-option">
                            <input type="radio" name="category" value="{{ $key }}" {{ request('category') == $key ? 'checked' : '' }} onchange="this.form.submit()">
                            {{ $name }}
                        </label>
                    @endforeach
                </div>

                <!-- Price Limit Filter -->
                <div class="filter-group">
                    <h3 class="filter-title">Harga Maksimal</h3>
                    <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="e.g. 50000" class="input-field" style="margin-bottom: 12px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 8px 16px; font-size: 13px;">Terapkan Harga</button>
                </div>

                <!-- Sort option hidden inside dropdown or pass through -->
                <input type="hidden" name="sort" id="hidden-sort" value="{{ request('sort', 'newest') }}">

                <!-- Reset Button -->
                <a href="{{ route('catalog') }}" class="btn btn-secondary" style="width: 100%; padding: 8px 16px; font-size: 13px; margin-top: 10px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            </form>
        </aside>

        <!-- Product Grid Area -->
        <section>
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

            <div class="catalog-header">
                <div class="catalog-title">
                    <h2>Menu Showcase</h2>
                    <p>Menampilkan {{ $products->count() }} produk manis berkualitas untuk Anda</p>
                </div>

                <div class="catalog-sort">
                    <label for="sort-select" style="font-size: 13px; font-weight: 600; margin-right: 8px; color: var(--text-muted);">Urutkan:</label>
                    <select id="sort-select" onchange="document.getElementById('hidden-sort').value = this.value; document.getElementById('filter-form').submit();">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="glass-card" style="text-align: center; padding: 60px 24px; border-radius: var(--radius-md);">
                    <span style="font-size: 64px; display: block; margin-bottom: 20px;">🥐❌</span>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 10px;">Produk tidak ditemukan</h3>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Maaf, tidak ada produk bakery yang cocok dengan filter atau kata kunci Anda.</p>
                    <a href="{{ route('catalog') }}" class="btn btn-primary">Kembali ke Semua Menu</a>
                </div>
            @else
                <div class="product-grid">
                    @foreach($products as $product)
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
                                    <div style="display: flex; flex-direction: column;">
                                        <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span style="font-size: 11px; color: {{ $product->stock > 0 ? 'var(--success)' : 'var(--error)' }}; font-weight: 600; margin-top: 2px;">
                                            {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok Habis' }}
                                        </span>
                                    </div>
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="add-to-cart-btn" title="Tambah ke Keranjang">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="add-to-cart-btn" style="background: #E5DDD3; color: #B5A89E; cursor: not-allowed;" disabled>
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>
</div>
@endsection
