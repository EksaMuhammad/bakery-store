@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="admin-title">
        <h1>Tambah Produk Baru</h1>
        <p>Isi formulir secara lengkap untuk mendaftarkan menu baru di toko</p>
    </div>
    <a href="{{ route('admin.products') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="glass-card" style="padding: 32px; max-width: 800px; margin: 0 auto 50px auto;">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Nama Produk <span style="color: var(--error);">*</span></label>
                <input type="text" name="name" id="name" class="input-field" value="{{ old('name') }}" placeholder="e.g. Croissant Almond Keju" required>
                @error('name') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="category">Kategori Menu <span style="color: var(--error);">*</span></label>
                <select name="category" id="category" class="input-field" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Harga Satuan (Rp) <span style="color: var(--error);">*</span></label>
                <input type="number" name="price" id="price" class="input-field" value="{{ old('price') }}" placeholder="e.g. 25000" min="0" required>
                @error('price') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="stock">Stok Awal <span style="color: var(--error);">*</span></label>
                <input type="number" name="stock" id="stock" class="input-field" value="{{ old('stock', 10) }}" placeholder="e.g. 15" min="0" required>
                @error('stock') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="image_file">Foto Produk (Maks. 2MB)</label>
            <input type="file" name="image_file" id="image_file" class="input-field" accept="image/*">
            <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Format didukung: JPG, PNG, JPEG, WEBP. Kosongkan untuk menggunakan placeholder default.</span>
            @error('image_file') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Produk <span style="color: var(--error);">*</span></label>
            <textarea name="description" id="description" class="input-field" rows="4" placeholder="Jelaskan aroma, tekstur, rasa, dan keunggulan roti ini..." required>{{ old('description') }}</textarea>
            @error('description') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="ingredients">Bahan Pembuatan (Ingredients)</label>
            <textarea name="ingredients" id="ingredients" class="input-field" rows="2" placeholder="e.g. Tepung gandum utuh, air, ragi, mentega Prancis, garam laut...">{{ old('ingredients') }}</textarea>
            @error('ingredients') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <label for="allergens">Informasi Alergen (Allergens) <span style="font-weight:normal; font-size:12px; color:var(--text-muted);">(Opsional)</span></label>
            <input type="text" name="allergens" id="allergens" class="input-field" value="{{ old('allergens') }}" placeholder="e.g. Gluten, Telur, Laktosa/Susu, Kacang-kacangan">
            @error('allergens') <span style="font-size:12px; color:var(--error); font-weight:500;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 16px; border-top: 1px solid rgba(229, 221, 211, 0.5); padding-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px;">
                <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Menu Baru
            </button>
            <a href="{{ route('admin.products') }}" class="btn btn-secondary" style="height: 48px; display: inline-flex; align-items: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
