@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Edit Produk: {{ $product->name }}</h1>
        <p>Perbarui informasi menu, stok, atau unggah foto baru</p>
    </div>
    <a href="{{ route('admin.products') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="glass-card" style="padding: 32px; max-width: 800px; margin: 0 auto 50px auto;">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Nama Produk <span style="color: var(--error);">*</span></label>
                <input type="text" name="name" id="name" class="input-field" value="{{ old('name', $product->name) }}" required>
                @error('name') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="category">Kategori Menu <span style="color: var(--error);">*</span></label>
                <select name="category" id="category" class="input-field" required>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Harga Satuan (Rp) <span style="color: var(--error);">*</span></label>
                <input type="number" name="price" id="price" class="input-field" value="{{ old('price', intval($product->price)) }}" min="0" required>
                @error('price') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="stock">Stok Tersedia <span style="color: var(--error);">*</span></label>
                <input type="number" name="stock" id="stock" class="input-field" value="{{ old('stock', $product->stock) }}" min="0" required>
                @error('stock') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group" style="display: flex; gap: 20px; align-items: center; background: var(--bg); padding: 15px; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
            <div style="text-align: center;">
                <label style="display:block; font-size:12px; margin-bottom: 5px;">Foto Saat Ini</label>
                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--surface-border);">
            </div>
            <div style="flex: 1;">
                <label for="image_file" style="margin-bottom: 8px;">Ganti Foto Produk (Maks. 2MB)</label>
                <input type="file" name="image_file" id="image_file" class="input-field" accept="image/*" style="background: white;">
                <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Format didukung: JPG, PNG, JPEG, WEBP. Biarkan kosong jika tidak ingin mengubah foto.</span>
                @error('image_file') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Produk <span style="color: var(--error);">*</span></label>
            <textarea name="description" id="description" class="input-field" rows="4" required>{{ old('description', $product->description) }}</textarea>
            @error('description') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="ingredients">Bahan Pembuatan (Ingredients)</label>
            <textarea name="ingredients" id="ingredients" class="input-field" rows="2">{{ old('ingredients', $product->ingredients) }}</textarea>
            @error('ingredients') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label for="allergens">Informasi Alergen (Allergens)</label>
            <input type="text" name="allergens" id="allergens" class="input-field" value="{{ old('allergens', $product->allergens) }}">
            @error('allergens') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 16px; border-top: 1px solid rgba(229, 221, 211, 0.5); padding-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px;">
                <i class="fa-solid fa-square-check"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.products') }}" class="btn btn-secondary" style="height: 48px; display: inline-flex; align-items: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
