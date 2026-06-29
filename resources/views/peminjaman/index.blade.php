<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Daftar Peminjaman</title>
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

        /* Alert Styling */
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

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
        }

        /* Table Card Section */
        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 28px;
            overflow-x: auto;
        }

        .recent-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            text-align: left;
        }

        .recent-table th {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            padding: 0 12px 16px 0;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .recent-table td {
            padding: 18px 12px 18px 0;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            vertical-align: middle;
        }

        .recent-table tr:last-child td {
            border-bottom: none;
        }

        .mhs-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mhs-name {
            font-weight: 600;
            color: var(--text-main);
        }

        .mhs-nim {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .book-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .book-title {
            font-weight: 500;
            color: var(--text-main);
        }

        .book-isbn {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .date-col {
            color: var(--text-muted);
            font-family: monospace;
            font-size: 0.85rem;
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

        .badge-kembali {
            background-color: rgba(203, 213, 225, 0.1);
            color: var(--silver-accent);
        }

        .btn-action {
            background-color: var(--accent-green);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Pagination */
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
            <li class="menu-item {{ request('filter') !== 'dikembalikan' ? 'active' : '' }}">
                <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
            </li>
            <li class="menu-item {{ request('filter') === 'dikembalikan' ? 'active' : '' }}">
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
        
        <!-- Header Bar -->
        <div class="header-bar">
            <div class="header-title">
                <h2>{{ request('filter') === 'dikembalikan' ? 'Data Pengembalian' : 'Transaksi Peminjaman' }}</h2>
            </div>
            @if(request('filter') !== 'dikembalikan')
                <a href="{{ route('peminjaman.create') }}" class="btn-add">+ Catat Peminjaman</a>
            @endif
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Daftar Peminjaman -->
        <div class="section-card">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $t)
                        <tr>
                            <td>
                                <div class="mhs-info">
                                    <span class="mhs-name">{{ $t->user->name }}</span>
                                    <span class="mhs-nim">NIM: {{ $t->user->nim ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="book-info">
                                    <span class="book-title">{{ $t->buku->judul }}</span>
                                    <span class="book-isbn">ISBN: {{ $t->buku->isbn }}</span>
                                </div>
                            </td>
                            <td class="date-col">{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="date-col">{{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}</td>
                            <td class="date-col">
                                {{ $t->tanggal_kembali_aktual ? \Carbon\Carbon::parse($t->tanggal_kembali_aktual)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @php
                                    $hariIni = now()->toDateString();
                                    $rencana = $t->tanggal_kembali_rencana;
                                    $selisih = \Carbon\Carbon::parse($rencana)->diffInDays(now(), false);
                                @endphp
                                
                                @if($t->status === 'dikembalikan')
                                    <span class="badge badge-kembali">Kembali</span>
                                @elseif($selisih > 0)
                                    <span class="badge badge-terlambat">Terlambat</span>
                                @elseif($selisih >= -1 && $selisih <= 0)
                                    <span class="badge badge-warning">Hampir jatuh</span>
                                @else
                                    <span class="badge badge-aktif">Aktif</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if($t->status === 'dipinjam')
                                    <form action="{{ route('peminjaman.kembalikan', $t->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn-action">Kembalikan</button>
                                    </form>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 500;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">Belum ada transaksi peminjaman tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($peminjamans->hasPages())
                <div class="pagination-container">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </div>

    </div>

</body>
</html>
