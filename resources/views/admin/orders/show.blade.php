@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Detail Pesanan: {{ $order->order_code }}</h1>
        <p>Kelola status pesanan, pembayaran, dan informasi pengiriman pelanggan</p>
    </div>
    <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="checkout-layout">
    
    <!-- Left: Order Details & Customer Info -->
    <div>
        <!-- Customer Info Card -->
        <div class="glass-card" style="padding: 28px; margin-bottom: 24px;">
            <h3 class="checkout-section-title"><i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 8px;"></i> Informasi Pelanggan</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 14px;">
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Lengkap:</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Nomor WhatsApp:</span>
                    <strong>{{ $order->customer_phone }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Email:</span>
                    <strong>{{ $order->customer_email }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Waktu Pemesanan:</span>
                    <strong>{{ $order->created_at->format('d M Y, H:i') }} WIB</strong>
                </div>
            </div>
        </div>

        <!-- Delivery & Address Card -->
        <div class="glass-card" style="padding: 28px; margin-bottom: 24px;">
            <h3 class="checkout-section-title"><i class="fa-solid fa-truck-ramp-box" style="color: var(--primary); margin-right: 8px;"></i> Metode Pengiriman & Pembayaran</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 14px; margin-bottom: 16px;">
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Tipe Pengiriman:</span>
                    <strong>{{ $order->delivery_type == 'delivery' ? '🛵 Kirim ke Alamat (Delivery)' : '🏪 Ambil Sendiri (Pickup)' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Metode Pembayaran:</span>
                    <strong>
                        @if($order->payment_method == 'transfer') 🏦 Transfer Bank
                        @elseif($order->payment_method == 'ewallet') 📱 E-Wallet (Gopay/OVO)
                        @else 💵 Cash On Delivery (COD)
                        @endif
                    </strong>
                </div>
            </div>

            @if($order->delivery_type == 'delivery')
                <div style="font-size: 14px; border-top: 1px solid rgba(229, 221, 211, 0.5); padding-top: 16px;">
                    <span style="color: var(--text-muted); display: block; margin-bottom: 6px;">Alamat Lengkap Pengiriman:</span>
                    <div style="background: var(--bg); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--surface-border); line-height: 1.6;">
                        {{ $order->address }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Ordered Items Table -->
        <div class="glass-card" style="padding: 28px;">
            <h3 class="checkout-section-title"><i class="fa-solid fa-basket-shopping" style="color: var(--primary); margin-right: 8px;"></i> Daftar Menu Dipesan</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding-bottom: 12px; border-bottom: 1px solid rgba(229, 221, 211, 0.3);">
                        <div style="display: flex; gap: 16px; align-items: center;">
                            @if($item->product)
                                <img src="{{ asset('images/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
                            @endif
                            <div>
                                <strong style="color: var(--secondary); font-size: 15px;">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</strong>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }} pcs
                                </div>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--secondary);">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 24px; font-size: 14px; display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                <div style="display: flex; width: 240px; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Subtotal Menu:</span>
                    <span>Rp {{ number_format($order->total_price - ($order->delivery_type == 'delivery' ? 10000 : 0), 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; width: 240px; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Biaya Kirim:</span>
                    <span>{{ $order->delivery_type == 'delivery' ? 'Rp 10.000' : 'Gratis' }}</span>
                </div>
                <div style="display: flex; width: 240px; justify-content: space-between; border-top: 2px solid var(--secondary); padding-top: 8px; font-weight: 800; font-size: 16px; color: var(--secondary);">
                    <span>Total Tagihan:</span>
                    <span style="color: var(--primary);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Update Status Sidebar Panel -->
    <aside class="glass-card summary-card">
        <h3 class="summary-title"><i class="fa-solid fa-bars-progress" style="color: var(--primary); margin-right: 8px;"></i> Status Pemrosesan</h3>
        
        <div style="margin-bottom: 24px;">
            <div style="font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Status Saat Ini:</div>
            @if($order->status == 'pending')
                <span class="status-badge status-pending" style="font-size:13px; padding: 6px 14px;">Pending</span>
            @elseif($order->status == 'processing')
                <span class="status-badge status-processing" style="font-size:13px; padding: 6px 14px;">Diproses (Dipanggang)</span>
            @elseif($order->status == 'completed')
                <span class="status-badge status-completed" style="font-size:13px; padding: 6px 14px;">Selesai (Diambil/Tiba)</span>
            @else
                <span class="status-badge status-cancelled" style="font-size:13px; padding: 6px 14px;">Dibatalkan</span>
            @endif
        </div>

        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="border-top: 1px solid rgba(229, 221, 211, 0.5); padding-top: 20px;">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="status">Ubah Status Menjadi:</label>
                <select name="status" id="status" class="input-field" required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses (Sedang Dipanggang)</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai (Sudah Diterima)</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; margin-top: 12px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Status Baru
            </button>
        </form>

        <div style="margin-top: 30px; border-top: 1px solid rgba(229, 221, 211, 0.5); padding-top: 20px; font-size: 13px; color: var(--text-muted); line-height: 1.6;">
            <strong>Catatan Petunjuk Status:</strong>
            <ul style="margin-top: 8px; padding-left: 16px;">
                <li><strong>Pending</strong>: Verifikasi transfer / pembayaran pelanggan.</li>
                <li><strong>Diproses</strong>: Dapur sedang memproses/memanggang roti.</li>
                <li><strong>Selesai</strong>: Roti diambil atau kurir menyelesaikan pengiriman.</li>
                <li><strong>Batal</strong>: Stok kosong atau dibatalkan (stok otomatis dikembalikan).</li>
            </ul>
        </div>
    </aside>

</div>
@endsection
