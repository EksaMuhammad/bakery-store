@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container">
    <div style="margin-top: 40px; margin-bottom: 10px;">
        <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary);">Keranjang Belanja</h1>
        <p style="color: var(--text-muted);">Selesaikan pesanan pastri & roti segar Anda</p>
    </div>

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

    @if(empty($cart))
        <!-- Empty Cart State -->
        <div class="glass-card" style="text-align: center; padding: 80px 24px; max-width: 700px; margin: 40px auto 80px auto;">
            <span style="font-size: 72px; display: block; margin-bottom: 24px;">🛒🍞</span>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--secondary); margin-bottom: 12px;">Keranjang Belanja Anda Kosong</h2>
            <p style="color: var(--text-muted); margin-bottom: 32px; max-width: 450px; margin-left: auto; margin-right: auto;">
                Aroma roti hangat sedang menunggu Anda! Silakan jelajahi katalog menu showcase kami untuk menambahkan kue favorit Anda ke keranjang.
            </p>
            <a href="{{ route('catalog') }}" class="btn btn-primary" style="padding: 14px 32px;">Jelajahi Menu Showcase</a>
        </div>
    @else
        <!-- Cart Layout -->
        <div class="cart-layout">
            
            <!-- Left: Cart Items List -->
            <div class="glass-card cart-items-wrapper">
                <div style="border-bottom: 1px solid rgba(229, 221, 211, 0.5); padding-bottom: 15px; margin-bottom: 15px; font-weight: 700; color: var(--secondary); font-size: 14px; display: grid; grid-template-columns: 100px 2fr 1fr 1fr auto; gap: 20px;">
                    <div>Menu</div>
                    <div>Deskripsi</div>
                    <div style="text-align: center;">Jumlah</div>
                    <div style="text-align: right;">Subtotal</div>
                    <div>Hapus</div>
                </div>

                @foreach($cart as $id => $item)
                    <div class="cart-item" id="cart-item-{{ $id }}">
                        <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['name'] }}" class="cart-item-img">
                        
                        <div>
                            <a href="{{ route('detail', $item['slug']) }}" class="cart-item-name">{{ $item['name'] }}</a>
                            <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                                Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs
                            </div>
                        </div>

                        <!-- Quantity Selector with dynamic AJAX update -->
                        <div style="display: flex; justify-content: center;">
                            <div class="quantity-control" style="height: 38px;">
                                <button type="button" class="qty-btn" style="width: 32px;" onclick="changeQty({{ $id }}, -1)">-</button>
                                <input type="number" id="qty-{{ $id }}" class="qty-input" style="width: 44px; font-size: 14px;" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}" readonly>
                                <button type="button" class="qty-btn" style="width: 32px;" onclick="changeQty({{ $id }}, 1)">+</button>
                            </div>
                        </div>

                        <div style="text-align: right; font-weight: 700; color: var(--secondary);" id="subtotal-{{ $id }}">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </div>

                        <div>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <button type="submit" class="btn btn-danger" style="width: 36px; height: 36px; padding: 0; border-radius: var(--radius-full);" title="Hapus dari keranjang">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right: Order Summary Panel -->
            <aside class="glass-card summary-card">
                <h3 class="summary-title">Ringkasan Pesanan</h3>
                
                <div class="summary-row">
                    <span>Subtotal Produk</span>
                    <span id="summary-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Pengiriman</span>
                    <span style="font-style: italic; font-size: 13px; color: var(--text-muted);">Dihitung di checkout</span>
                </div>
                
                <div class="summary-row summary-row-total">
                    <span>Total Harga</span>
                    <span id="summary-total" style="color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; height: 50px; margin-top: 20px; font-size: 16px;">
                    Lanjut Ke Pembayaran <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </a>
                
                <a href="{{ route('catalog') }}" class="btn btn-secondary" style="width: 100%; height: 50px; margin-top: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-bag-shopping"></i> Kembali Belanja
                </a>
            </aside>

        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function changeQty(productId, delta) {
        const qtyField = document.getElementById(`qty-${productId}`);
        let currentQty = parseInt(qtyField.value);
        let targetQty = currentQty + delta;
        const maxStock = parseInt(qtyField.getAttribute('max'));

        if (targetQty < 1) return;
        if (targetQty > maxStock) {
            alert(`Stok tidak mencukupi. Maksimal stok tersedia: ${maxStock} pcs`);
            return;
        }

        // Perform AJAX Fetch request to update cart session
        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: targetQty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity field
                qtyField.value = targetQty;
                // Update subtotal
                document.getElementById(`subtotal-${productId}`).innerText = `Rp ${data.subtotal}`;
                // Update cart total
                document.getElementById('summary-subtotal').innerText = `Rp ${data.total}`;
                document.getElementById('summary-total').innerText = `Rp ${data.total}`;
                
                // Update navbar cart count
                const navCartIcon = document.getElementById('cart-icon-btn');
                let countSpan = navCartIcon.querySelector('.cart-count');
                
                // Get all quantities to sum up
                let allQuantities = 0;
                document.querySelectorAll('.qty-input').forEach(input => {
                    allQuantities += parseInt(input.value);
                });

                if (allQuantities > 0) {
                    if (countSpan) {
                        countSpan.innerText = allQuantities;
                    } else {
                        const newCountSpan = document.createElement('span');
                        newCountSpan.className = 'cart-count';
                        newCountSpan.innerText = allQuantities;
                        navCartIcon.appendChild(newCountSpan);
                    }
                } else {
                    if (countSpan) countSpan.remove();
                }
            } else {
                alert(data.message || 'Gagal memperbarui keranjang.');
            }
        })
        .catch(err => {
            console.error("AJAX Error: ", err);
            alert('Terjadi kesalahan jaringan.');
        });
    }
</script>
@endsection
