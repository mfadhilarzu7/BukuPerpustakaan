<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukuKita — Tambah Buku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- ZXing: library barcode scanner yang reliable untuk EAN-13/ISBN -->
    <script src="https://unpkg.com/@zxing/library@0.21.3/umd/index.min.js"></script>

    <style>
        /* ══ Design Tokens (sama dengan dashboard) ══ */
        :root {
            --bg-dark:      #090b11;
            --bg-sidebar:   #11141d;
            --card-bg:      #151824;
            --border-color: #212638;
            --text-main:    #f8fafc;
            --text-muted:   #64748b;
            --accent-green: #10b981;
            --accent-red:   #ef4444;
            --accent-blue:  #3b82f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ══ Sidebar ══ */
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
            gap: 4px;
        }
        .sidebar-menu { flex-grow: 1; }
        .sidebar-menu-bottom { margin-top: auto; }

        .menu-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s;
            font-size: 0.92rem;
        }
        .menu-item.active a {
            background-color: rgba(255,255,255,0.06);
            color: var(--text-main);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .menu-item a:hover { color: var(--text-main); background-color: rgba(255,255,255,0.03); }
        .menu-item.active.scan-item a { color: var(--text-main); }

        /* ══ Main Content ══ */
        .main-container {
            margin-left: 240px;
            flex-grow: 1;
            padding: 36px 44px;
            min-height: 100vh;
        }

        /* ══ Top Header ══ */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header-actions { display: flex; align-items: center; gap: 12px; }

        .btn-header {
            padding: 9px 18px;
            border-radius: 9999px;
            font-family: inherit;
            font-weight: 500;
            font-size: 0.84rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: 1px solid var(--border-color);
        }
        .btn-header-add {
            background: #1e293b;
            color: var(--text-main);
        }
        .btn-header-add:hover { background: #334155; }
        .btn-header-logout {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
            border-color: rgba(239,68,68,0.2);
        }
        .btn-header-logout:hover { background: rgba(239,68,68,0.18); }
        .profile-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #1e293b;
            border: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem;
        }

        /* ══ Form Card ══ */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 36px 40px;
            max-width: 900px;
        }
        .form-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 28px;
            color: var(--text-main);
        }

        /* ── ISBN Row ── */
        .isbn-section { margin-bottom: 28px; }
        .isbn-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }
        .isbn-row {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }
        .isbn-input {
            flex: 1;
            padding: 12px 16px;
            background: #0f1320;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .isbn-input::placeholder { color: var(--text-muted); }
        .isbn-input:focus {
            border-color: rgba(255,255,255,0.25);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.04);
        }
        .btn-scan {
            padding: 12px 18px;
            background: #1e293b;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .btn-scan:hover { background: #293548; border-color: #3d4f66; }
        .btn-cari {
            padding: 12px 20px;
            background: #1e293b;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .btn-cari:hover { background: #293548; border-color: #3d4f66; }
        .btn-cari:disabled { opacity: 0.5; cursor: not-allowed; }

        .isbn-hint {
            margin-top: 8px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* ── Status bar ── */
        .isbn-status {
            margin-top: 10px;
            font-size: 0.82rem;
            font-weight: 500;
            min-height: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .status-loading { color: #94a3b8; }
        .status-success { color: var(--accent-green); }
        .status-error   { color: var(--accent-red); }

        /* ── Cover Preview mini (hidden field helper) ── */
        .cover-preview-row {
            display: none;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 14px 18px;
            background: rgba(16,185,129,0.06);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 10px;
        }
        .cover-preview-row.visible { display: flex; }
        .cover-mini {
            width: 56px; height: 78px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }
        .cover-info { flex: 1; }
        .cover-info-title { font-size: 0.88rem; font-weight: 600; color: var(--text-main); }
        .cover-info-sub { font-size: 0.76rem; color: var(--text-muted); margin-top: 3px; }

        /* ── Form Fields Grid ── */
        .field-divider {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 0 0 28px 0;
        }
        .field-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .field-grid-1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .field-group { display: flex; flex-direction: column; gap: 8px; }

        .field-label {
            font-size: 0.79rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .field-label span { color: var(--accent-red); }

        .field-input,
        .field-textarea {
            padding: 12px 16px;
            background: #0f1320;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.3s;
        }
        .field-input::placeholder,
        .field-textarea::placeholder { color: var(--text-muted); }
        .field-input:focus,
        .field-textarea:focus {
            border-color: rgba(255,255,255,0.22);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.04);
        }
        .field-input.filled {
            border-color: rgba(16,185,129,0.4);
            background: rgba(16,185,129,0.05);
        }
        .field-textarea { resize: vertical; min-height: 90px; }

        /* ── Form Footer ── */
        .form-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .btn-batal {
            padding: 12px 24px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-muted);
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }
        .btn-batal:hover { color: var(--text-main); border-color: rgba(255,255,255,0.2); }

        .btn-simpan {
            padding: 12px 28px;
            background: var(--text-main);
            color: #0f1320;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
        }
        .btn-simpan:hover { background: #e2e8f0; transform: translateY(-1px); }

        /* ══ Barcode Scanner Modal ══ */
        .scanner-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(6px);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .scanner-overlay.active { display: flex; }
        .scanner-modal {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            width: 420px;
            max-width: 95vw;
            overflow: hidden;
        }
        .scanner-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .scanner-header h3 { font-size: 1rem; font-weight: 700; }
        .scanner-close {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .scanner-close:hover { color: var(--text-main); background: rgba(255,255,255,0.1); }
        .scanner-body { padding: 20px 24px; }
        #scanner-viewport {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            border: 1px solid var(--border-color);
        }
        /* Override style html5-qrcode */
        #scanner-viewport img { display: none !important; }
        #qr-shaded-region { border-color: var(--accent-green) !important; }
        .scanner-hint {
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 14px;
        }
        .scanner-result {
            margin-top: 14px;
            padding: 12px 16px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 10px;
            font-size: 0.875rem;
            color: var(--accent-green);
            display: none;
            font-weight: 600;
            text-align: center;
        }

        /* Spinner */
        .spin {
            display: inline-block;
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.2);
            border-top-color: var(--text-main);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            padding: 14px 16px;
            color: var(--accent-red);
            font-size: 0.875rem;
            margin-bottom: 24px;
        }
        .alert-error ul { margin: 6px 0 0 16px; }

        /* Responsive */
        @media (max-width: 680px) {
            .field-grid-2 { grid-template-columns: 1fr; }
            .isbn-row { flex-wrap: wrap; }
            .main-container { padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- ══ Sidebar Kiri ══ -->
    <div class="sidebar">
        <div class="sidebar-brand">BukuKita</div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="/dashboard-petugas">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
            </li>
            <li class="menu-item">
                <a href="/katalog">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    Katalog Buku
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index') }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
                    Peminjaman
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('peminjaman.index', ['filter' => 'dikembalikan']) }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                    Pengembalian
                </a>
            </li>
            <li class="menu-item active">
                <a href="{{ route('buku.create') }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Scan ISBN
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('denda.index') }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Denda
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu-bottom">
            <li class="menu-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                <a href="#" onclick="document.getElementById('logout-form').submit();" style="color:#ef4444">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Keluar
                </a>
            </li>
        </ul>
    </div>

    <!-- ══ Main Content ══ -->
    <div class="main-container">

        <!-- Header Bar -->
        <div class="header-bar">
            <div class="header-title">Scan ISBN Baru</div>
            <div class="header-actions">
                <a href="{{ route('buku.create') }}" class="btn-header btn-header-add">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Buku
                </a>
                <a href="#" onclick="document.getElementById('logout-form').submit();" class="btn-header btn-header-logout">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Keluar
                </a>
                <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'AB', 0, 2)) }}</div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-title">Tambah Buku Baru</div>

            @if ($errors->any())
            <div class="alert-error">
                <strong>Terdapat kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('buku.store') }}" method="POST" id="form-tambah-buku">
                @csrf
                <input type="hidden" id="cover_url"  name="cover_url"  value="{{ old('cover_url') }}">
                <input type="hidden" id="deskripsi_hidden" name="deskripsi" value="{{ old('deskripsi') }}">

                <!-- ── ISBN Search ── -->
                <div class="isbn-section">
                    <div class="isbn-label">Nomor ISBN</div>
                    <div class="isbn-row">
                        <input type="text" id="isbn_search" class="isbn-input"
                               placeholder="Contoh: 9786026232779"
                               maxlength="13" inputmode="numeric"
                               value="{{ old('isbn') }}">
                        <button type="button" class="btn-scan" id="btn-scan" onclick="bukaScanner()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zm0 9.75c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zm9.75-9.75c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zm0 9.75h.75v.75h-.75v-.75zm9.75-9.75h.75v.75h-.75v-.75zm-3 3.75h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm3 0h.75v.75h-.75v-.75zm-3 3h.75v.75h-.75v-.75zm3 0h.75v.75h-.75v-.75z"/>
                            </svg>
                            Scan Barcode
                        </button>
                        <button type="button" class="btn-cari" id="btn-cari" onclick="cariBuku()">
                            <span id="cari-icon">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                            </span>
                            <span id="cari-spinner" class="spin" style="display:none"></span>
                            Cari Buku
                        </button>
                    </div>
                    <div class="isbn-hint">Masukkan 10 atau 13 digit ISBN atau gunakan kamera Anda untuk memindai barcode buku.</div>
                    <div class="isbn-status" id="isbn-status"></div>
                </div>

                <!-- ── Cover Preview (muncul setelah auto-fill) ── -->
                <div class="cover-preview-row" id="cover-preview-row">
                    <img class="cover-mini" id="cover-mini-img" src="" alt="Cover">
                    <div class="cover-info">
                        <div class="cover-info-title" id="cover-info-title">—</div>
                        <div class="cover-info-sub">Cover berhasil dimuat dari Google Books</div>
                    </div>
                </div>

                <hr class="field-divider">

                <!-- ── Row 1: Judul + Penulis ── -->
                <div class="field-grid-2">
                    <div class="field-group">
                        <label class="field-label" for="judul">Judul Buku <span>*</span></label>
                        <input type="text" id="judul" name="judul" class="field-input"
                               placeholder="Judul buku" value="{{ old('judul') }}" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="penulis">Penulis / Pengarang <span>*</span></label>
                        <input type="text" id="penulis" name="penulis" class="field-input"
                               placeholder="Nama penulis" value="{{ old('penulis') }}" required>
                    </div>
                </div>

                <!-- ── Row 2: Penerbit + Tahun ── -->
                <div class="field-grid-2">
                    <div class="field-group">
                        <label class="field-label" for="penerbit">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" class="field-input"
                               placeholder="Nama penerbit" value="{{ old('penerbit') }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" class="field-input"
                               placeholder="Contoh: 2023" min="1900" max="{{ date('Y') }}"
                               value="{{ old('tahun_terbit') }}">
                    </div>
                </div>

                <!-- ── Row 3: Stok ── -->
                <div class="field-grid-2">
                    <div class="field-group">
                        <label class="field-label" for="stok">Jumlah Stok Buku <span>*</span></label>
                        <input type="number" id="stok" name="stok" class="field-input"
                               value="{{ old('stok', 1) }}" min="0" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="isbn">ISBN (untuk disimpan) <span>*</span></label>
                        <input type="text" id="isbn" name="isbn" class="field-input"
                               placeholder="Otomatis terisi dari kolom atas" value="{{ old('isbn') }}" required>
                    </div>
                </div>

                <!-- ── Deskripsi ── -->
                <div class="field-grid-1">
                    <div class="field-group">
                        <label class="field-label" for="deskripsi_field">Deskripsi / Sinopsis</label>
                        <textarea id="deskripsi_field" class="field-textarea"
                                  placeholder="Sinopsis atau deskripsi singkat buku...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <!-- ── Footer ── -->
                <div class="form-footer">
                    <a href="{{ route('buku.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ Barcode Scanner Modal ══ -->
    <div class="scanner-overlay" id="scanner-overlay">
        <div class="scanner-modal">
            <div class="scanner-header">
                <h3>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:6px;vertical-align:middle"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"/></svg>
                    Scan Barcode ISBN
                </h3>
                <button class="scanner-close" onclick="tutupScanner()">✕</button>
            </div>
            <div class="scanner-body">
                <!-- Video langsung dari ZXing, lebih reliable -->
                <div style="position:relative; border-radius:12px; overflow:hidden; background:#000; border:1px solid var(--border-color);">
                    <video id="scanner-video"
                           style="width:100%; display:block; max-height:300px; object-fit:cover;"
                           autoplay muted playsinline></video>
                    <!-- Garis bidik di tengah -->
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
                        <div style="width:80%;height:70px;border:2px solid #10b981;border-radius:8px;box-shadow:0 0 0 9999px rgba(0,0,0,0.45);"></div>
                    </div>
                </div>
                <div class="scanner-hint">Arahkan barcode ISBN ke dalam kotak hijau</div>
                <!-- Status scanner -->
                <div id="scanner-status" style="margin-top:10px;font-size:0.8rem;color:var(--text-muted);text-align:center;">Memulai kamera...</div>
                <div class="scanner-result" id="scanner-result"></div>
            </div>
        </div>
    </div>

    <script>
    // ═══════════════════════════════════════
    //  Auto-fill via Google Books API
    // ═══════════════════════════════════════
    const AUTO_FILL = ['judul', 'penulis', 'penerbit', 'tahun_terbit'];

    async function cariBuku() {
        const isbnVal = document.getElementById('isbn_search').value.trim();
        if (!isbnVal) { setStatus('error', 'Masukkan nomor ISBN terlebih dahulu.'); return; }

        setLoading(true);
        setStatus('loading', 'Mencari di Google Books...');

        try {
            const res  = await fetch(`/api/isbn/${isbnVal}`);
            const data = await res.json();

            if (!res.ok) {
                setStatus('error', '✕ ' + (data.message || 'Buku tidak ditemukan.'));
                setLoading(false);
                return;
            }

            // Isi ISBN ke kedua field
            document.getElementById('isbn_search').value = isbnVal;
            document.getElementById('isbn').value         = isbnVal;

            // Auto-fill field teks
            const map = {
                judul:        data.judul,
                penulis:      data.penulis,
                penerbit:     data.penerbit,
                tahun_terbit: data.tahun_terbit,
            };
            for (const [id, val] of Object.entries(map)) {
                if (val) {
                    const el = document.getElementById(id);
                    el.value = val;
                    el.classList.add('filled');
                    setTimeout(() => el.classList.remove('filled'), 4000);
                }
            }

            // Deskripsi
            if (data.deskripsi) {
                document.getElementById('deskripsi_field').value    = data.deskripsi;
                document.getElementById('deskripsi_hidden').value   = data.deskripsi;
            }

            // Sync deskripsi ke hidden saat diketik
            document.getElementById('deskripsi_field').addEventListener('input', function() {
                document.getElementById('deskripsi_hidden').value = this.value;
            });

            // Cover
            if (data.cover_url) {
                document.getElementById('cover_url').value = data.cover_url;
                const img = document.getElementById('cover-mini-img');
                img.src = data.cover_url;
                document.getElementById('cover-info-title').textContent = data.judul || '—';
                document.getElementById('cover-preview-row').classList.add('visible');
            }

            setStatus('success', '✓ Data buku berhasil ditemukan dan terisi otomatis!');
        } catch (e) {
            setStatus('error', '✕ Gagal terhubung ke server. Coba lagi.');
        } finally {
            setLoading(false);
        }
    }

    function setLoading(on) {
        document.getElementById('btn-cari').disabled  = on;
        document.getElementById('cari-icon').style.display    = on ? 'none' : 'inline';
        document.getElementById('cari-spinner').style.display = on ? 'inline-block' : 'none';
    }
    function setStatus(type, msg) {
        const el = document.getElementById('isbn-status');
        el.className = 'isbn-status status-' + type;
        el.textContent = msg;
    }

    // Enter key → cari buku
    document.getElementById('isbn_search').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); cariBuku(); }
    });

    // Sync deskripsi
    document.getElementById('deskripsi_field').addEventListener('input', function() {
        document.getElementById('deskripsi_hidden').value = this.value;
    });

    // ═══════════════════════════════════════
    //  Barcode Scanner — ZXing Library
    // ═══════════════════════════════════════
    let zxingReader = null;
    let scannerAktif = false;

    async function bukaScanner() {
        // Tunggu ZXing siap dimuat
        if (typeof ZXing === 'undefined') {
            setStatus('error', '✕ Library scanner belum siap, coba refresh halaman.');
            return;
        }

        document.getElementById('scanner-overlay').classList.add('active');
        document.getElementById('scanner-result').style.display = 'none';
        setScannerStatus('Memulai kamera...');
        scannerAktif = true;

        try {
            zxingReader = new ZXing.BrowserMultiFormatReader();

            // Cari kamera — preferensi kamera belakang
            const devices = await zxingReader.listVideoInputDevices();
            setScannerStatus(`Kamera ditemukan: ${devices.length}. Memulai scan...`);

            let deviceId = undefined;
            if (devices.length > 0) {
                // Cari kamera belakang
                const back = devices.find(d =>
                    /back|rear|environment/i.test(d.label)
                );
                deviceId = back ? back.deviceId : devices[0].deviceId;
            }

            const video = document.getElementById('scanner-video');

            await zxingReader.decodeFromVideoDevice(
                deviceId,
                video,
                (result, err, controls) => {
                    if (!scannerAktif) return;

                    if (result) {
                        const isbn = result.getText();

                        // Tampil hasil sebentar lalu tutup
                        const resEl = document.getElementById('scanner-result');
                        resEl.textContent = '✓ ISBN terdeteksi: ' + isbn;
                        resEl.style.display = 'block';

                        // Isi field ISBN
                        document.getElementById('isbn_search').value = isbn;

                        // Tutup scanner & cari buku
                        setTimeout(() => {
                            tutupScanner();
                            setTimeout(() => cariBuku(), 300);
                        }, 800);
                    }
                    // err adalah normal (tiap frame yg tidak terbaca) — abaikan
                }
            );

            setScannerStatus('Siap — arahkan barcode ke kotak hijau');

        } catch (err) {
            setScannerStatus('Error: ' + err.message);
            setStatus('error', '✕ Tidak bisa mengakses kamera: ' + err.message);
            // Jika kamera tidak tersedia, tawarkan input manual
            setTimeout(tutupScanner, 2500);
        }
    }

    function tutupScanner() {
        scannerAktif = false;
        document.getElementById('scanner-overlay').classList.remove('active');
        if (zxingReader) {
            try { zxingReader.reset(); } catch(e) {}
            zxingReader = null;
        }
    }

    function setScannerStatus(msg) {
        const el = document.getElementById('scanner-status');
        if (el) el.textContent = msg;
    }

    // Tutup modal klik di luar
    document.getElementById('scanner-overlay').addEventListener('click', function(e) {
        if (e.target === this) tutupScanner();
    });

    // ESC tutup scanner
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') tutupScanner();
    });
    </script>
</body>
</html>