@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_code)

@section('content')
<div class="container" style="padding: 20px 0;">
    
    @if(session('success'))
        <div class="alert alert-success" style="max-width: 650px; margin: 20px auto 0 auto;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="glass-card invoice-card">
        
        <!-- Header -->
        <div class="invoice-header">
            <span style="font-size: 40px; display: block; margin-bottom: 12px;">🎉</span>
            <div class="invoice-header-title">SweetCrust Bakery</div>
            <div class="invoice-header-desc">Terima kasih atas pembelian Anda!</div>
            
            <div style="margin-top: 20px; font-family: monospace; font-size: 18px; font-weight: 700; color: var(--secondary); background: var(--bg); padding: 10px; border-radius: var(--radius-sm); border: 1px solid var(--surface-border); display: inline-block;">
                {{ $order->order_code }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">
                Tanggal Pemesanan: {{ $order->created_at->format('d M Y, H:i') }} WIB
            </div>
        </div>

        <!-- Order Status Section -->
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 1px dashed var(--surface-border); padding-bottom: 30px;">
            <div style="font-size: 14px; font-weight: 700; color: var(--secondary); margin-bottom: 10px;">Status Pesanan:</div>
            
            @if($order->status == 'pending')
                <span class="status-badge status-pending"><i class="fa-solid fa-hourglass-half"></i> Menunggu Konfirmasi</span>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 10px; line-height: 1.5;">
                    Pembayaran Anda sedang kami verifikasi. Admin akan segera memproses adonan roti Anda.
                </p>
            @elseif($order->status == 'processing')
                <span class="status-badge status-processing"><i class="fa-solid fa-fire-burner"></i> Sedang Dipanggang / Diproses</span>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 10px; line-height: 1.5;">
                    Chef kami sedang memanggang produk pesanan segar Anda di dalam oven.
                </p>
            @elseif($order->status == 'completed')
                <span class="status-badge status-completed"><i class="fa-solid fa-circle-check"></i> Pesanan Selesai</span>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 10px; line-height: 1.5;">
                    Pesanan telah berhasil diambil atau kurir telah menyelesaikan pengiriman. Selamat menikmati!
                </p>
            @else
                <span class="status-badge status-cancelled"><i class="fa-solid fa-ban"></i> Pesanan Dibatalkan</span>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 10px; line-height: 1.5;">
                    Pesanan dibatalkan karena kendala stock atau pembatalan dari pihak admin.
                </p>
            @endif
        </div>

        <!-- Customer & Order details -->
        <div style="margin-bottom: 30px; border-bottom: 1px dashed var(--surface-border); padding-bottom: 24px;">
            <h4 style="font-size: 16px; font-weight: 700; color: var(--secondary); margin-bottom: 16px;">Detail Penerima & Pengiriman</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Nama Penerima:</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">No. WhatsApp:</span>
                    <strong>{{ $order->customer_phone }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Jenis Penerimaan:</span>
                    <strong>{{ $order->delivery_type == 'delivery' ? 'Kirim ke Alamat (Delivery)' : 'Ambil di Toko (Pickup)' }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Metode Pembayaran:</span>
                    <strong>
                        @if($order->payment_method == 'transfer') Transfer Bank
                        @elseif($order->payment_method == 'ewallet') Saldo E-Wallet
                        @else Cash On Delivery (COD)
                        @endif
                    </strong>
                </div>
            </div>

            @if($order->delivery_type == 'delivery')
                <div style="margin-top: 16px; font-size: 14px;">
                    <span style="color: var(--text-muted); display: block; margin-bottom: 4px;">Alamat Tujuan:</span>
                    <div style="background: var(--bg); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--surface-border); line-height: 1.6;">
                        {{ $order->address }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Items Table -->
        <div style="margin-bottom: 30px;">
            <h4 style="font-size: 16px; font-weight: 700; color: var(--secondary); margin-bottom: 16px;">Rincian Pesanan</h4>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(229, 221, 211, 0.3);">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            @if($item->product)
                                <img src="{{ asset('images/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
                            @endif
                            <div>
                                <strong>{{ $item->product ? $item->product->name : 'Produk Terhapus' }}</strong>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                    {{ $item->quantity }} pcs x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--secondary);">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 20px; font-size: 14px; display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                <div style="display: flex; width: 240px; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Biaya Roti:</span>
                    <span>Rp {{ number_format($order->total_price - ($order->delivery_type == 'delivery' ? 10000 : 0), 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; width: 240px; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Ongkir:</span>
                    <span>{{ $order->delivery_type == 'delivery' ? 'Rp 10.000' : 'Gratis' }}</span>
                </div>
                <div style="display: flex; width: 240px; justify-content: space-between; border-top: 2px solid var(--secondary); padding-top: 8px; font-weight: 800; font-size: 16px; color: var(--secondary);">
                    <span>Total Bayar:</span>
                    <span style="color: var(--primary);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('catalog') }}" class="btn btn-primary" style="padding: 10px 24px;">
                <i class="fa-solid fa-cookie-bite"></i> Kembali ke Menu
            </a>
            <button onclick="window.print()" class="btn btn-secondary" style="padding: 10px 24px;">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
        </div>

    </div>
</div>
@endsection
