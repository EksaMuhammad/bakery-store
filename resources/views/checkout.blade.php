@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container">
    <div style="margin-top: 40px; margin-bottom: 20px;">
        <h1 style="font-size: 32px; font-weight: 800; color: var(--secondary);">Konfirmasi Pemesanan</h1>
        <p style="color: var(--text-muted);">Lengkapi detail pengiriman dan pembayaran di bawah ini</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="checkout-layout">
        
        <!-- Left: Checkout Forms -->
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <!-- Customer Information Section -->
            <div class="glass-card" style="padding: 28px; margin-bottom: 24px;">
                <h3 class="checkout-section-title"><i class="fa-regular fa-id-card" style="color: var(--primary); margin-right: 8px;"></i> Data Diri Pelanggan</h3>
                
                <div class="form-group">
                    <label for="customer_name">Nama Lengkap</label>
                    <input type="text" name="customer_name" id="customer_name" class="input-field" value="{{ old('customer_name') }}" placeholder="e.g. John Doe" required>
                    @error('customer_name') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="customer_email">Alamat Email</label>
                        <input type="email" name="customer_email" id="customer_email" class="input-field" value="{{ old('customer_email') }}" placeholder="e.g. john@example.com" required>
                        @error('customer_email') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">Nomor WhatsApp</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="input-field" value="{{ old('customer_phone') }}" placeholder="e.g. 081234567890" required>
                        @error('customer_phone') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Delivery / Pickup Option Section -->
            <div class="glass-card" style="padding: 28px; margin-bottom: 24px;">
                <h3 class="checkout-section-title"><i class="fa-solid fa-truck" style="color: var(--primary); margin-right: 8px;"></i> Metode Penerimaan</h3>
                
                <div class="delivery-options">
                    <label class="delivery-option active" id="option-pickup" onclick="setDeliveryType('pickup')">
                        <input type="radio" name="delivery_type" value="pickup" checked>
                        <div class="delivery-option-title"><i class="fa-solid fa-store"></i> Ambil di Toko (Pickup)</div>
                        <div class="delivery-option-desc">Ambil langsung secara gratis di SweetCrust Bakery</div>
                    </label>

                    <label class="delivery-option" id="option-delivery" onclick="setDeliveryType('delivery')">
                        <input type="radio" name="delivery_type" value="delivery">
                        <div class="delivery-option-title"><i class="fa-solid fa-motorcycle"></i> Kirim ke Alamat (Delivery)</div>
                        <div class="delivery-option-desc">Kurir kami mengantar (+Rp 10.000 ongkir)</div>
                    </label>
                </div>

                <!-- Textarea Address, hidden by default -->
                <div class="form-group" id="address-container" style="display: none; transition: var(--transition);">
                    <label for="address">Alamat Lengkap Pengiriman</label>
                    <textarea name="address" id="address" class="input-field" rows="3" placeholder="Tuliskan alamat lengkap beserta instruksi pengiriman (e.g. RT/RW, nomor rumah, warna pagar)...">{{ old('address') }}</textarea>
                    @error('address') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Payment Methods Section -->
            <div class="glass-card" style="padding: 28px; margin-bottom: 24px;">
                <h3 class="checkout-section-title"><i class="fa-regular fa-credit-card" style="color: var(--primary); margin-right: 8px;"></i> Metode Pembayaran</h3>
                
                <div class="form-group">
                    <label class="filter-option" style="padding: 12px; border: 1px solid var(--surface-border); border-radius: var(--radius-sm); background: white; margin-bottom: 12px;">
                        <input type="radio" name="payment_method" value="transfer" checked required>
                        <div>
                            <strong>Transfer Bank (Otomatis / Manual)</strong>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">BCA: 872-0392-121 a/n SweetCrust Bakery</p>
                        </div>
                    </label>

                    <label class="filter-option" style="padding: 12px; border: 1px solid var(--surface-border); border-radius: var(--radius-sm); background: white; margin-bottom: 12px;">
                        <input type="radio" name="payment_method" value="ewallet" required>
                        <div>
                            <strong>E-Wallet (Gopay / OVO / Dana)</strong>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Gopay/OVO: 0812-3456-7890</p>
                        </div>
                    </label>

                    <label class="filter-option" style="padding: 12px; border: 1px solid var(--surface-border); border-radius: var(--radius-sm); background: white;">
                        <input type="radio" name="payment_method" value="cod" required>
                        <div>
                            <strong>Cash On Delivery (Bayar di Tempat)</strong>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Bayar tunai ke kurir saat pesanan Anda tiba di rumah.</p>
                        </div>
                    </label>
                </div>
            </div>
        </form>

        <!-- Right: Order Summary Panel -->
        <aside class="glass-card summary-card">
            <h3 class="summary-title">Ringkasan Keranjang</h3>
            
            <div style="max-height: 240px; overflow-y: auto; margin-bottom: 20px; border-bottom: 1px solid rgba(229, 221, 211, 0.5); padding-bottom: 15px;">
                @foreach($cart as $id => $item)
                    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 12px;">
                        <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
                        <div style="flex: 1;">
                            <h4 style="font-size: 14px; font-weight: 700; color: var(--secondary);">{{ $item['name'] }}</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">{{ $item['quantity'] }} pcs x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <span style="font-weight: 700; font-size: 14px; color: var(--secondary);">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="summary-row">
                <span>Subtotal Menu</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <div class="summary-row">
                <span>Biaya Pengiriman</span>
                <span id="delivery-fee-text">Gratis (Ambil di Toko)</span>
            </div>
            
            <div class="summary-row summary-row-total">
                <span>Total Pembayaran</span>
                <span id="checkout-total" style="color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="submit" form="checkout-form" class="btn btn-primary" style="width: 100%; height: 50px; margin-top: 24px; font-size: 16px;">
                Buat Pesanan Sekarang <i class="fa-solid fa-circle-check"></i>
            </button>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="{{ route('cart.index') }}" style="font-size: 13px; font-weight: 600; color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang</a>
            </div>
        </aside>

    </div>
</div>
@endsection

@section('scripts')
<script>
    const subtotal = parseInt("{{ $total }}");
    const checkoutTotal = document.getElementById('checkout-total');
    const deliveryFeeText = document.getElementById('delivery-fee-text');
    const addressContainer = document.getElementById('address-container');
    const addressField = document.getElementById('address');
    
    function setDeliveryType(type) {
        const optionPickup = document.getElementById('option-pickup');
        const optionDelivery = document.getElementById('option-delivery');
        
        // Remove active class from both
        optionPickup.classList.remove('active');
        optionDelivery.classList.remove('active');
        
        // Find corresponding radio and check it
        const radio = document.querySelector(`input[name="delivery_type"][value="${type}"]`);
        radio.checked = true;

        if (type === 'delivery') {
            optionDelivery.classList.add('active');
            addressContainer.style.display = 'block';
            addressField.required = true;
            
            // Recalculate total with shipping fee 10.000
            const totalWithShipping = subtotal + 10000;
            deliveryFeeText.innerText = "Rp 10.000";
            checkoutTotal.innerText = "Rp " + formatNumber(totalWithShipping);
        } else {
            optionPickup.classList.add('active');
            addressContainer.style.display = 'none';
            addressField.required = false;
            
            // Reset to original subtotal
            deliveryFeeText.innerText = "Gratis (Ambil di Toko)";
            checkoutTotal.innerText = "Rp " + formatNumber(subtotal);
        }
    }

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }

    // Run on boot to handle old input state redirection
    @if(old('delivery_type') == 'delivery')
        setDeliveryType('delivery');
    @endif
</script>
@endsection
