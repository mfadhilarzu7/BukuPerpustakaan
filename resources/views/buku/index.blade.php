<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Daftar Buku</title>
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

        .header-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .btn-add {
            background-color: #1e293b;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: 9999px;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-add:hover {
            background-color: #334155;
            border-color: #475569;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--accent-green);
        }

        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 28px;
        }

        .recent-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .recent-table th {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .recent-table td {
            padding: 18px 0;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            vertical-align: middle;
            padding-right: 16px;
        }

        .recent-table tr:last-child td { border-bottom: none; }

        .book-title {
            font-weight: 600;
            color: var(--text-main);
            display: block;
        }

        .book-isbn {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .text-muted {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-stok-ok {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .badge-stok-habis {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        .action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            padding: 7px 14px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: 1px solid var(--border-color);
            background-color: #1e293b;
            color: var(--text-main);
            display: inline-block;
        }

        .btn-sm:hover {
            background-color: #334155;
        }

        .btn-sm-danger {
            background-color: rgba(239, 68, 68, 0.08);
            border-color: rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
        }

        .btn-sm-danger:hover {
            background-color: rgba(239, 68, 68, 0.15);
        }

        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- Sidebar Kiri -->
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

        <div class="header-bar">
            <div class="header-title">
                <h2>Katalog Buku</h2>
                <div class="header-sub">Kelola koleksi buku perpustakaan</div>
            </div>
            <a href="{{ route('buku.create') }}" class="btn-add">+ Tambah Buku</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="section-card">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Judul & ISBN</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td>
                                <span class="book-title">{{ $buku->judul }}</span>
                                <span class="book-isbn">ISBN: {{ $buku->isbn }}</span>
                            </td>
                            <td class="text-muted">{{ $buku->penulis ?? '-' }}</td>
                            <td class="text-muted">{{ $buku->penerbit ?? '-' }}</td>
                            <td class="text-muted">{{ $buku->tahun_terbit ?? '-' }}</td>
                            <td>
                                @if($buku->stok > 0)
                                    <span class="badge badge-stok-ok">{{ $buku->stok }} pcs</span>
                                @else
                                    <span class="badge badge-stok-habis">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn-sm">Edit</a>
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-sm-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                Belum ada buku yang terdaftar. <a href="{{ route('buku.create') }}" style="color: var(--silver-accent);">Tambahkan sekarang →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>