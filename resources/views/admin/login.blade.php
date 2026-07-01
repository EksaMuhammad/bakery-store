<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SweetCrust Bakery</title>
    
    <!-- Link to custom vanilla styling -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle, var(--bg) 60%, #E5DDD3 100%);
        }
    </style>
</head>
<body>

    <div class="glass-card" style="width: 100%; max-width: 440px; padding: 40px; text-align: center;">
        <a href="{{ route('home') }}" style="font-size: 32px; font-weight: 800; color: var(--secondary);">
            <i class="fa-solid fa-wheat-awn" style="color: var(--primary);"></i> Sweet<span>Crust</span>
        </a>
        <h2 style="font-size: 18px; font-weight: 600; color: var(--text-muted); margin-top: 8px; margin-bottom: 30px;">
            Halaman Kelola Toko (Admin Panel)
        </h2>

        @if(session('error'))
            <div class="alert alert-error" style="padding: 12px; margin-bottom: 20px; font-size: 13px;">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" style="padding: 12px; margin-bottom: 20px; font-size: 13px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" style="text-align: left;">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Admin</label>
                <input type="email" name="email" id="email" class="input-field" value="{{ old('email', 'admin@sweetcrust.com') }}" placeholder="admin@sweetcrust.com" required>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="password" style="margin-bottom: 0;">Password</label>
                    <span style="font-size: 11px; color: var(--primary); font-weight: 600;">Default: admin123</span>
                </div>
                <input type="password" name="password" id="password" class="input-field" value="admin123" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; font-size: 15px;">
                Masuk Dashboard <i class="fa-solid fa-right-to-bracket"></i>
            </button>
        </form>
        
        <div style="margin-top: 24px;">
            <a href="{{ route('home') }}" style="font-size: 13px; font-weight: 600; color: var(--text-muted);">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
            </a>
        </div>
    </div>

</body>
</html>
