<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Catat Peminjaman</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #090b11;
            --bg-sidebar: #11141d;
            --card-bg: #151824;
            --border-color: #212638;
            --text-main: #f8fafc;
            --text-muted: #64748b;
            --silver-accent: #cbd5e1;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 240px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-grow: 1;
        }

        .sidebar-menu-bottom {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: auto;
        }

        .menu-item a {
            display: block;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .menu-item.active a {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .menu-item a:hover {
            color: var(--text-main);
            background-color: rgba(255, 255, 255, 0.03);
        }

        /* Main Content */
        .main-container {
            margin-left: 240px;
            flex-grow: 1;
            padding: 40px;
            max-width: 800px;
        }

        /* Top Header */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .header-title h2 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Form Card Section */
        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 35px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: rgba(15, 23, 42, 0.5);
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--silver-accent);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.05);
        }

        .error-message {
            color: var(--accent-red);
            font-size: 0.8rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Action Buttons Row */
        .action-row {
            display: flex;
            gap: 12px;
            margin-top: 35px;
            border-top: 1px solid var(--border-color);
            padding-top: 25px;
        }

        .btn-submit {
            background-color: var(--text-main);
            color: var(--bg-dark);
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #1e293b;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 14px 28px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-secondary:hover {
            background-color: #334155;
            border-color: #475569;
        }
    </style>
</head>
<body>

    <!-- Sidebar Kiri -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <span>BukuKita</span>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="menu-item">
                <a href="/katalog">Katalog Buku</a>
            </li>
            <li class="menu-item active">
                <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
            </li>
            <li class="menu-item">
                <a href="#">Pengembalian</a>
            </li>
            <li class="menu-item">
                <a href="/buku/create">Scan ISBN</a>
            </li>
            <li class="menu-item">
                <a href="#">Denda</a>
            </li>
        </ul>

        <ul class="sidebar-menu-bottom">
            <li class="menu-item">
                <a href="#">Pengguna</a>
            </li>
            <li class="menu-item">
                <a href="#">Pengaturan</a>
            </li>
            <li class="menu-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444; font-weight: bold;">
                    Keluar / Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="main-container">
        
        <!-- Header Bar -->
        <div class="header-bar">
            <div class="header-title">
                <h2>Catat Peminjaman Baru</h2>
            </div>
        </div>

        <!-- Form Card Section -->
        <div class="section-card">
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <!-- Pilihan Mahasiswa -->
                <div class="form-group">
                    <label for="user_id">Pilih Mahasiswa</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}" {{ old('user_id') == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->name }} (NIM: {{ $mhs->nim ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pilihan Buku -->
                <div class="form-group">
                    <label for="buku_id">Pilih Buku</label>
                    <select name="buku_id" id="buku_id" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                {{ $buku->judul }} (Stok: {{ $buku->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Pinjam -->
                <div class="form-group">
                    <label for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input 
                        type="date" 
                        name="tanggal_pinjam" 
                        id="tanggal_pinjam" 
                        class="form-control" 
                        value="{{ old('tanggal_pinjam', now()->toDateString()) }}" 
                        required
                    >
                    @error('tanggal_pinjam')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Rencana Kembali -->
                <div class="form-group">
                    <label for="tanggal_kembali_rencana">Batas Tanggal Pengembalian</label>
                    <input 
                        type="date" 
                        name="tanggal_kembali_rencana" 
                        id="tanggal_kembali_rencana" 
                        class="form-control" 
                        value="{{ old('tanggal_kembali_rencana', now()->addDays(7)->toDateString()) }}" 
                        required
                    >
                    @error('tanggal_kembali_rencana')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Aksi Form -->
                <div class="action-row">
                    <button type="submit" class="btn-submit">Simpan Transaksi</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn-secondary">Kembali</a>
                </div>

            </form>
        </div>

    </div>

</body>
</html>
