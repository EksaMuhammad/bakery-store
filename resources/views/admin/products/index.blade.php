@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Kelola Produk Bakery</h1>
        <p>Kelola menu showcase, stok roti, harga, dan foto produk</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Produk Baru
    </a>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 80px;">Foto</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Satuan</th>
                <th style="text-align: center;">Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
                    </td>
                    <td>
                        <strong style="color: var(--secondary); font-size: 15px;">{{ $product->name }}</strong>
                        <div style="font-size: 12px; color: var(--text-muted); line-height: 1.4; max-width: 320px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            {{ $product->description }}
                        </div>
                    </td>
                    <td>
                        <span class="detail-cat" style="margin: 0; font-size: 11px; padding: 2px 8px;">
                            @if($product->category == 'breads') Roti
                            @elseif($product->category == 'cakes') Cake
                            @elseif($product->category == 'pastries') Pastri
                            @else Cookies
                            @endif
                        </span>
                    </td>
                    <td style="font-weight: 700; color: var(--secondary);">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; color: {{ $product->stock > 5 ? 'var(--success)' : 'var(--error)' }};">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-sm);">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-sm);">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                        Belum ada produk terdaftar di database.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
