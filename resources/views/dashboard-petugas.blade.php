<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Dashboard Admin</title>
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
            overflow-x: hidden;
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

        /* Main Content Container */
        .main-container {
            margin-left: 240px;
            flex-grow: 1;
            padding: 40px;
            max-width: 1300px;
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
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

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-val {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .stat-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Layout Grid for Lists */
        .layout-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* Card Section Component */
        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 28px;
            display: flex;
            flex-direction: column;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.2s ease;
        }

        .section-link:hover {
            color: var(--text-main);
        }

        /* Table Styling */
        .recent-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-bottom: 30px;
        }

        .recent-table th {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .recent-table td {
            padding: 16px 0;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            vertical-align: middle;
        }

        .recent-table tr:last-child td {
            border-bottom: none;
        }

        .mhs-name {
            font-weight: 500;
            color: var(--text-main);
        }

        .book-name {
            color: var(--text-muted);
        }

        .date-col {
            color: var(--text-muted);
            font-family: monospace;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
        }

        .badge-aktif {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .badge-terlambat {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--accent-yellow);
        }

        /* Action Buttons Row */
        .action-row {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .btn-secondary {
            background-color: #1e293b;
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 12px 20px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #334155;
            border-color: #475569;
        }

        /* Popular Books List */
        .popular-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .popular-item {
            background-color: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .popular-item:hover {
            background-color: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .popular-cover-placeholder {
            width: 44px;
            height: 60px;
            background-color: #1e293b;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
        }

        .popular-cover-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .popular-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .popular-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .popular-author {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Top Description Banner */
        .top-description {
            font-size: 1.1rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 25px;
            letter-spacing: -0.2px;
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
            <li class="menu-item active">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="menu-item">
                <a href="/katalog">Katalog Buku</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index', ['filter' => 'dikembalikan']) }}">Pengembalian</a>
            </li>
            <li class="menu-item">
                <a href="/buku/create">Scan ISBN</a>
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
        
        <!-- Judul Banner Tambahan -->
        <div class="top-description">
            BukuKita — dashboard admin perpustakaan kampus dengan tema hitam silver
        </div>

        <!-- Header Bar -->
        <div class="header-bar">
            <div class="header-title">
                <h2>Dashboard</h2>
            </div>
            <div class="header-actions">
                <a href="{{ route('buku.create') }}" class="btn-add">+ Tambah Buku</a>
                <div class="profile-avatar">AD</div>
            </div>
        </div>

        <!-- Grid Statistik -->
        <div class="stats-grid">
            
            <!-- TOTAL BUKU -->
            <div class="stat-card">
                <span class="stat-label">Total Buku</span>
                <span class="stat-val">{{ number_format($totalBuku) }}</span>
                <span class="stat-sub">↑ {{ $uniqueBukuCount }} judul buku</span>
            </div>

            <!-- DIPINJAM -->
            <div class="stat-card">
                <span class="stat-label">Dipinjam</span>
                <span class="stat-val">{{ $dipinjamCount }}</span>
                <span class="stat-sub">dari {{ $totalMahasiswa }} mahasiswa</span>
            </div>

            <!-- TERLAMBAT -->
            <div class="stat-card">
                <span class="stat-label">Terlambat</span>
                <span class="stat-val">{{ $terlambatCount }}</span>
                <span class="stat-sub"><span style="color: #ef4444;">perlu tindak lanjut</span></span>
            </div>

            <!-- DENDA AKTIF -->
            <div class="stat-card">
                <span class="stat-label">Denda Aktif</span>
                <span class="stat-val">Rp {{ number_format($totalDenda / 1000, 0) }}k</span>
                <span class="stat-sub">belum dilunasi</span>
            </div>

        </div>

        <!-- Layout Utama -->
        <div class="layout-grid">

            <!-- Kolom Kiri: Transaksi Terbaru -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Transaksi Terbaru</span>
                    <a href="#" class="section-link">Lihat semua →</a>
                </div>
                
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Buku</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                            <tr>
                                <td class="mhs-name">{{ $t->user->name }}</td>
                                <td class="book-name">{{ $t->buku->judul }}</td>
                                <td class="date-col">{{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $hariIni = now()->toDateString();
                                        $rencana = $t->tanggal_kembali_rencana;
                                        $selisih = \Carbon\Carbon::parse($rencana)->diffInDays(now(), false);
                                    @endphp
                                    
                                    @if($t->status === 'dikembalikan')
                                        <span class="badge badge-aktif">Kembali</span>
                                    @elseif($selisih > 0)
                                        <span class="badge badge-terlambat">Terlambat</span>
                                    @elseif($selisih >= -1 && $selisih <= 0)
                                        <span class="badge badge-warning">Hampir jatuh</span>
                                    @else
                                        <span class="badge badge-aktif">Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="action-row">
                    <a href="{{ route('buku.create') }}" class="btn-secondary">Scan ISBN Baru</a>
                    <button class="btn-secondary">Ekspor Laporan</button>
                </div>
            </div>

            <!-- Kolom Kanan: Buku Populer -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Buku Populer</span>
                    <a href="#" class="section-link">Semua →</a>
                </div>

                <div class="popular-list">
                    @forelse($bukuPopuler as $b)
                        <div class="popular-item">
                            <div class="popular-cover-placeholder">
                                @if($b->cover_url)
                                    <img src="{{ $b->cover_url }}" alt="Cover {{ $b->judul }}">
                                @else
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="color: var(--text-muted);">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="popular-info">
                                <span class="popular-title">{{ $b->judul }}</span>
                                <span class="popular-author">{{ $b->penulis }} · {{ $b->peminjamans_count }}x dipinjam</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">Belum ada data buku populer.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</body>
</html>
