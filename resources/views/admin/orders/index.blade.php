@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Kelola Pesanan Masuk</h1>
        <p>Pantau status pembayaran, jenis pengiriman, dan proses memanggang pesanan</p>
    </div>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Tipe</th>
                <th>Pembayaran</th>
                <th>Waktu Transaksi</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td style="font-family: monospace; font-weight: 700; color: var(--secondary); font-size: 15px;">{{ $order->order_code }}</td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $order->customer_email }} | {{ $order->customer_phone }}</div>
                    </td>
                    <td>
                        <span style="font-size: 13px;">
                            {{ $order->delivery_type == 'delivery' ? '🛵 Delivery' : '🏪 Ambil Sendiri' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 13px; font-weight: 500;">
                            @if($order->payment_method == 'transfer') 🏦 Transfer
                            @elseif($order->payment_method == 'ewallet') 📱 E-Wallet
                            @else 💵 COD
                            @endif
                        </span>
                    </td>
                    <td style="font-size: 13px;">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                    <td style="font-weight: 700; color: var(--secondary);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status == 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @elseif($order->status == 'processing')
                            <span class="status-badge status-processing">Diproses</span>
                        @elseif($order->status == 'completed')
                            <span class="status-badge status-completed">Selesai</span>
                        @else
                            <span class="status-badge status-cancelled">Batal</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-sm);">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                        Belum ada pesanan masuk saat ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
