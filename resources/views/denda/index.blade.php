<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Daftar Denda</title>
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
            max-width: 1200px;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 35px;
        }

        .header-title h2 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-sub {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .total-denda-badge {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
            padding: 12px 20px;
            border-radius: 12px;
            text-align: right;
        }

        .total-denda-badge .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .total-denda-badge .amount {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-top: 2px;
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

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
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

        .recent-table tr:last-child td {
            border-bottom: none;
        }

        .mhs-info, .book-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mhs-name, .book-title { font-weight: 600; color: var(--text-main); }
        .mhs-nim, .book-isbn {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .date-col {
            color: var(--text-muted);
            font-family: monospace;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .denda-amount {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-red);
        }

        .hari-terlambat {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        .badge-belum {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

        .badge-lunas {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
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
            white-space: nowrap;
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-1px);
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
            <li class="menu-item">
                <a href="{{ route('buku.index') }}">Katalog Buku</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index') }}">Pengembalian</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('buku.create') }}">Scan ISBN</a>
            </li>
            <li class="menu-item active">
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
                <h2>Daftar Denda</h2>
                <div class="header-sub">Kelola pembayaran denda keterlambatan pengembalian buku</div>
            </div>
            <div class="total-denda-badge">
                <div class="label">Total Belum Lunas</div>
                <div class="amount">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabel Denda -->
        <div class="section-card">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Buku Dipinjam</th>
                        <th>Batas Kembali</th>
                        <th>Hari Terlambat</th>
                        <th>Total Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendas as $denda)
                        <tr>
                            <td>
                                <div class="mhs-info">
                                    <span class="mhs-name">{{ $denda->peminjaman->user->name }}</span>
                                    <span class="mhs-nim">NIM: {{ $denda->peminjaman->user->nim ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="book-info">
                                    <span class="book-title">{{ $denda->peminjaman->buku->judul }}</span>
                                    <span class="book-isbn">{{ $denda->peminjaman->buku->isbn }}</span>
                                </div>
                            </td>
                            <td class="date-col">
                                {{ \Carbon\Carbon::parse($denda->peminjaman->tanggal_kembali_rencana)->format('d M Y') }}
                            </td>
                            <td>
                                <span class="hari-terlambat">{{ $denda->hari_terlambat }} hari</span>
                            </td>
                            <td>
                                <span class="denda-amount">
                                    Rp {{ number_format($denda->total_denda, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($denda->status_bayar === 'lunas')
                                    <span class="badge badge-lunas">Lunas</span>
                                @else
                                    <span class="badge badge-belum">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                @if($denda->status_bayar === 'belum')
                                    <form action="{{ route('denda.lunasi', $denda->id) }}" method="POST" onsubmit="return confirm('Tandai denda ini sebagai lunas?');">
                                        @csrf
                                        <button type="submit" class="btn-action">Tandai Lunas</button>
                                    </form>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 50px 0; font-size: 1rem;">
                                Belum ada catatan denda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($dendas->hasPages())
                <div class="pagination-container">
                    {{ $dendas->links() }}
                </div>
            @endif
        </div>

    </div>

</body>
</html>
