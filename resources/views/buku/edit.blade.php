<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Edit Buku</title>
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
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

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
        }

        .sidebar-menu, .sidebar-menu-bottom {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-menu { flex-grow: 1; }
        .sidebar-menu-bottom { margin-top: auto; }

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

        .main-container {
            margin-left: 240px;
            flex-grow: 1;
            padding: 40px;
            max-width: 800px;
        }

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

        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 35px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            font-weight: 500;
        }

        .action-row {
            display: flex;
            gap: 12px;
            margin-top: 30px;
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

        .btn-submit:hover { opacity: 0.9; }

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
        }

        .btn-secondary:hover { background-color: #334155; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">BukuKita</div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="menu-item active">
                <a href="{{ route('buku.index') }}">Katalog Buku</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index', ['filter' => 'dikembalikan']) }}">Pengembalian</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('buku.create') }}">Scan ISBN</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('denda.index') }}">Denda</a>
            </li>
        </ul>
        <ul class="sidebar-menu-bottom">
            <li class="menu-item"><a href="#">Pengguna</a></li>
            <li class="menu-item"><a href="#">Pengaturan</a></li>
            <li class="menu-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444; font-weight: bold;">Keluar / Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-container">
        <div class="header-bar">
            <div class="header-title">
                <h2>Edit Buku</h2>
            </div>
        </div>

        <div class="section-card">
            <form action="{{ route('buku.update', $buku->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="isbn">ISBN</label>
                        <input type="text" name="isbn" id="isbn" class="form-control" value="{{ old('isbn', $buku->isbn) }}" required>
                        @error('isbn') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="judul">Judul Buku</label>
                        <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $buku->judul) }}" required>
                        @error('judul') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="penulis">Penulis</label>
                        <input type="text" name="penulis" id="penulis" class="form-control" value="{{ old('penulis', $buku->penulis) }}" required>
                        @error('penulis') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="form-control" value="{{ old('penerbit', $buku->penerbit) }}">
                    </div>

                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="{{ date('Y') }}">
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', $buku->stok) }}" min="0" required>
                        @error('stok') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="{{ route('buku.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
