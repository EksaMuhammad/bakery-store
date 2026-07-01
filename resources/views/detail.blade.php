@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    
    @if(session('success'))
        <div class="alert alert-success" style="margin-top: 24px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="margin-top: 24px;">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Main Detail Layout -->
    <div class="detail-layout">
        
        <!-- Left: Image Showcase -->
        <div class="detail-img-wrapper">
            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="detail-img">
        </div>

        <!-- Right: Detail Info & Checkout Action -->
        <div class="detail-info">
            <span class="detail-cat">
                @if($product->category == 'breads') Roti (Breads)
                @elseif($product->category == 'cakes') Kue (Cakes)
                @elseif($product->category == 'pastries') Pastri (Pastries)
                @else Kue Kering (Cookies)
                @endif
            </span>
            
            <h1>{{ $product->name }}</h1>
            
            <div class="detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            
            <p class="detail-desc">{{ $product->description }}</p>

            <!-- Metadata: Ingredients & Allergens -->
            <div class="detail-meta">
                <div class="meta-item">
                    <div class="meta-label"><i class="fa-solid fa-seedling"></i> Bahan Pembuatan (Ingredients):</div>
                    <div class="meta-val">{{ $product->ingredients ?? 'Tepung terigu, air, gula, ragi alami.' }}</div>
                </div>
                @if($product->allergens)
                    <div class="meta-item" style="margin-top: 15px;">
                        <div class="meta-label" style="color: var(--error);"><i class="fa-solid fa-triangle-exclamation"></i> Informasi Alergen (Allergens):</div>
                        <div class="meta-val" style="font-weight: 500;">Mengandung {{ $product->allergens }}</div>
                    </div>
                @endif
            </div>

            <!-- Form: Add to Cart -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-weight: 700; font-size: 15px; color: var(--secondary);">Jumlah:</span>
                        <div class="quantity-control">
                            <button type="button" class="qty-btn" onclick="decrementQty()">-</button>
                            <input type="number" name="quantity" id="quantity-input" class="qty-input" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" class="qty-btn" onclick="incrementQty()">+</button>
                        </div>
                        <span style="font-size: 13px; color: var(--success); font-weight: 600;">
                            Stok tersedia: <strong>{{ $product->stock }}</strong> pcs
                        </span>
                    </div>

                    <div style="display: flex; gap: 16px; margin-top: 10px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; height: 50px;">
                            <i class="fa-solid fa-cart-plus"></i> Tambah Ke Keranjang
                        </button>
                        <a href="{{ route('catalog') }}" class="btn btn-secondary" style="height: 50px;">
                            Kembali Belanja
                        </a>
                    </div>
                </form>
            @else
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-xmark"></i> Mohon maaf, produk ini sedang habis dan dalam proses pemanggangan kembali.
                </div>
                <a href="{{ route('catalog') }}" class="btn btn-primary" style="width: 100%; height: 50px;">
                    Cari Menu Lainnya
                </a>
            @endif
        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="products-section" style="border-top: 1px solid var(--surface-border); padding-top: 60px; margin-bottom: 60px;">
            <div class="section-header" style="text-align: left; margin-bottom: 36px;">
                <h2 style="font-size: 26px;">Rekomendasi Lainnya</h2>
                <p>Menu serupa yang mungkin menarik perhatian Anda.</p>
            </div>
            
            <div class="product-grid" style="grid-template-columns: repeat(3, 1fr);">
                @foreach($relatedProducts as $relProduct)
                    <div class="product-card">
                        <div class="product-img-wrapper" style="height: 200px;">
                            <img src="{{ asset('images/products/' . $relProduct->image) }}" alt="{{ $relProduct->name }}" class="product-img">
                        </div>
                        <div class="product-body">
                            <a href="{{ route('detail', $relProduct->slug) }}" class="product-title" style="font-size: 16px;">{{ $relProduct->name }}</a>
                            <p class="product-desc" style="font-size: 12px; height: 36px; -webkit-line-clamp: 2;">{{ $relProduct->description }}</p>
                            
                            <div class="product-footer" style="padding-top: 12px;">
                                <span class="product-price" style="font-size: 16px;">Rp {{ number_format($relProduct->price, 0, ',', '.') }}</span>
                                <a href="{{ route('detail', $relProduct->slug) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-sm);">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const qtyInput = document.getElementById('quantity-input');
    const maxStock = parseInt("{{ $product->stock }}");

    function incrementQty() {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal < maxStock) {
            qtyInput.value = currentVal + 1;
        }
    }

    function decrementQty() {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal > 1) {
            qtyInput.value = currentVal - 1;
        }
    }
</script>
@endsection
