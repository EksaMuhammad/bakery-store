@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Dashboard Statistik</h1>
        <p>Ikhtisar performa penjualan SweetCrust Bakery saat ini</p>
    </div>
    <div style="font-weight: 600; color: var(--secondary);">
        Halo, {{ session('admin_name', 'Admin') }} <i class="fa-solid fa-circle-user" style="color: var(--primary); margin-left: 6px;"></i>
    </div>
</div>

<!-- Stats Dashboard Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title"><i class="fa-solid fa-wallet" style="color: var(--success); margin-right: 6px;"></i> Pendapatan Bersih</div>
        <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Pesanan dengan status "Selesai"</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title"><i class="fa-solid fa-hourglass-half" style="color: var(--warning); margin-right: 6px;"></i> Pendapatan Tertunda</div>
        <div class="stat-value" style="color: var(--warning);">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Pesanan pending/diproses</div>
    </div>

    <div class="stat-card">
        <div class="stat-title"><i class="fa-solid fa-receipt" style="color: var(--primary); margin-right: 6px;"></i> Total Transaksi</div>
        <div class="stat-value">{{ $totalOrders }}</div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">{{ $completedOrders }} pesanan selesai sukses</div>
    </div>

    <div class="stat-card">
        <div class="stat-title"><i class="fa-solid fa-cookie-bite" style="color: var(--secondary); margin-right: 6px;"></i> Menu Aktif</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Jenis produk di showcase</div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="admin-title" style="margin-bottom: 20px;">
    <h2>Pesanan Terbaru Masuk</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Penerimaan</th>
                <th>Waktu</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td style="font-family: monospace; font-weight: 700; color: var(--secondary);">{{ $order->order_code }}</td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $order->customer_phone }}</div>
                    </td>
                    <td>
                        <span style="font-size: 13px;">
                            {{ $order->delivery_type == 'delivery' ? '🛵 Delivery' : '🏪 Pickup' }}
                        </span>
                    </td>
                    <td style="font-size: 13px;">{{ $order->created_at->format('d M Y, H:i') }}</td>
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
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                        Belum ada pesanan masuk saat ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
