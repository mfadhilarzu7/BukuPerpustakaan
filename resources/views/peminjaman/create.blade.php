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
            --accent-indigo: #6366f1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
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

        /* Main Content */
        .main-container {
            margin-left: 240px;
            flex-grow: 1;
            padding: 40px;
            max-width: 860px;
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

        /* Form Card */
        .section-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 35px;
        }
        .form-group { margin-bottom: 24px; }
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

        /* ISBN Input Row */
        .isbn-row {
            display: flex;
            gap: 10px;
        }
        .isbn-row .form-control {
            flex-grow: 1;
        }
        .btn-lookup {
            padding: 14px 22px;
            border-radius: 10px;
            border: none;
            background: var(--accent-indigo);
            color: white;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-lookup:hover { background: #4f46e5; transform: translateY(-1px); }
        .btn-lookup:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* Book Preview Card */
        #buku-preview {
            display: none;
            margin-top: 16px;
            background: rgba(99, 102, 241, 0.07);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 14px;
            padding: 20px;
            gap: 18px;
            flex-direction: row;
            align-items: flex-start;
            animation: fadeIn 0.3s ease;
        }
        #buku-preview.show { display: flex; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        #buku-preview .preview-cover {
            width: 68px;
            height: 90px;
            border-radius: 8px;
            background: #1e293b;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #buku-preview .preview-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #buku-preview .preview-cover .no-cover {
            color: var(--text-muted);
            font-size: 1.6rem;
        }
        #buku-preview .preview-info { flex-grow: 1; }
        #buku-preview .preview-judul {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
        }
        #buku-preview .preview-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
        }
        #buku-preview .preview-stok {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .stok-ada {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .stok-habis {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Error state for ISBN */
        #isbn-error {
            display: none;
            margin-top: 10px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            padding: 12px 16px;
            color: #f87171;
            font-size: 0.85rem;
            font-weight: 500;
        }
        #isbn-error.show { display: block; }

        /* Action Buttons */
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
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
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
        .btn-secondary:hover { background-color: #334155; border-color: #475569; }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 6px;
            vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Section divider */
        .section-divider {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-bottom: 24px;
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
            <li class="menu-item"><a href="/dashboard">Dashboard</a></li>
            <li class="menu-item"><a href="/katalog">Katalog Buku</a></li>
            <li class="menu-item active"><a href="{{ route('peminjaman.index') }}">Peminjaman</a></li>
            <li class="menu-item"><a href="{{ route('denda.index') }}">Denda</a></li>
            <li class="menu-item"><a href="/buku/create">Scan ISBN</a></li>
            <li class="menu-item"><a href="{{ route('buku.index') }}">Data Buku</a></li>
        </ul>
        <ul class="sidebar-menu-bottom">
            <li class="menu-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#ef4444;font-weight:bold;">
                    Keluar / Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="main-container">
        
        <div class="header-bar">
            <div class="header-title">
                <h2>Catat Peminjaman Baru</h2>
                <p style="color:var(--text-muted);font-size:0.9rem;margin-top:4px;">Scan atau masukkan ISBN untuk mencari buku</p>
            </div>
        </div>

        <!-- Alert pesan error dari session -->
        @if(session('error'))
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:14px 18px;margin-bottom:24px;color:#f87171;font-size:0.9rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="section-card">
            <form id="peminjaman-form" action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <!-- === BAGIAN 1: MAHASISWA === -->
                <div class="section-divider">1 · Pilih Mahasiswa</div>

                <div class="form-group">
                    <label for="user_id">Nama / NIM Mahasiswa</label>
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

                <!-- === BAGIAN 2: BUKU BERDASARKAN ISBN === -->
                <div class="section-divider">2 · Cari Buku via ISBN</div>

                <!-- Hidden field buku_id yang akan diisi oleh JS setelah lookup -->
                <input type="hidden" name="isbn" id="isbn-hidden" value="{{ old('isbn') }}">

                <div class="form-group">
                    <label for="isbn-input">Kode ISBN Buku</label>
                    <div class="isbn-row">
                        <input 
                            type="text" 
                            id="isbn-input"
                            class="form-control" 
                            placeholder="Contoh: 9786026232242"
                            value="{{ old('isbn') }}"
                            inputmode="numeric"
                            autocomplete="off"
                        >
                        <button type="button" id="btn-cari-isbn" class="btn-lookup" onclick="lookupIsbn()">
                            🔍 Cari Buku
                        </button>
                    </div>

                    @error('isbn')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <!-- Error dari AJAX -->
                    <div id="isbn-error"></div>

                    <!-- Preview buku ditemukan -->
                    <div id="buku-preview">
                        <div class="preview-cover">
                            <img id="preview-cover-img" src="" alt="Cover" style="display:none;">
                            <div id="preview-cover-placeholder" class="no-cover">📚</div>
                        </div>
                        <div class="preview-info">
                            <div class="preview-judul" id="preview-judul">—</div>
                            <div class="preview-meta" id="preview-penulis">—</div>
                            <div class="preview-meta" id="preview-penerbit"></div>
                            <div class="preview-meta" id="preview-isbn" style="font-family:monospace;color:#6366f1;font-size:0.8rem;margin-top:4px;"></div>
                            <div id="preview-stok-wrapper" style="margin-top:10px;"></div>
                        </div>
                    </div>
                </div>

                <!-- === BAGIAN 3: TANGGAL === -->
                <div class="section-divider">3 · Tanggal Peminjaman</div>

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
                    <button type="submit" id="btn-submit" class="btn-submit" disabled>
                        Simpan Transaksi
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn-secondary">Kembali</a>
                </div>

            </form>
        </div>

    </div>

    <script>
        // Jika ada ISBN dari old() (setelah validation fail), coba lookup otomatis
        window.addEventListener('DOMContentLoaded', function () {
            const oldIsbn = '{{ old('isbn') }}';
            if (oldIsbn) {
                document.getElementById('isbn-input').value = oldIsbn;
                lookupIsbn();
            }
        });

        // Izinkan tekan Enter pada field ISBN untuk cari
        document.getElementById('isbn-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupIsbn();
            }
        });

        let bukuStok = 0;

        async function lookupIsbn() {
            const isbnInput = document.getElementById('isbn-input');
            const isbn = isbnInput.value.trim();
            const btn = document.getElementById('btn-cari-isbn');
            const btnSubmit = document.getElementById('btn-submit');
            const preview = document.getElementById('buku-preview');
            const isbnError = document.getElementById('isbn-error');
            const isbnHidden = document.getElementById('isbn-hidden');

            // Reset state
            preview.classList.remove('show');
            isbnError.classList.remove('show');
            isbnError.textContent = '';
            isbnHidden.value = '';
            btnSubmit.disabled = true;

            if (!isbn) {
                isbnError.textContent = '⚠ Masukkan nomor ISBN terlebih dahulu.';
                isbnError.classList.add('show');
                return;
            }

            // Show loading state
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Mencari...';

            try {
                const res = await fetch(`/api/buku-by-isbn/${isbn}`);
                const data = await res.json();

                if (!res.ok) {
                    isbnError.textContent = '❌ ' + (data.message || 'Buku tidak ditemukan di database.');
                    isbnError.classList.add('show');
                    return;
                }

                // Populate preview
                document.getElementById('preview-judul').textContent = data.judul;
                document.getElementById('preview-penulis').textContent = 'Penulis: ' + data.penulis;
                document.getElementById('preview-penerbit').textContent = data.penerbit !== '-' ? 'Penerbit: ' + data.penerbit : '';
                document.getElementById('preview-isbn').textContent = 'ISBN: ' + data.isbn;

                // Cover
                const coverImg = document.getElementById('preview-cover-img');
                const coverPlaceholder = document.getElementById('preview-cover-placeholder');
                if (data.cover_url) {
                    coverImg.src = data.cover_url;
                    coverImg.style.display = 'block';
                    coverPlaceholder.style.display = 'none';
                } else {
                    coverImg.style.display = 'none';
                    coverPlaceholder.style.display = 'flex';
                }

                // Stok badge
                bukuStok = parseInt(data.stok);
                const stokWrapper = document.getElementById('preview-stok-wrapper');
                if (bukuStok > 0) {
                    stokWrapper.innerHTML = `<span class="preview-stok stok-ada">✓ Tersedia: ${bukuStok} pcs</span>`;
                    // Set hidden isbn field dan aktifkan submit
                    isbnHidden.value = data.isbn;
                    btnSubmit.disabled = false;
                } else {
                    stokWrapper.innerHTML = `<span class="preview-stok stok-habis">✗ Stok Habis — Tidak dapat dipinjam</span>`;
                    btnSubmit.disabled = true;
                }

                preview.classList.add('show');

            } catch (err) {
                isbnError.textContent = '❌ Terjadi kesalahan koneksi. Coba lagi.';
                isbnError.classList.add('show');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '🔍 Cari Buku';
            }
        }
    </script>

</body>
</html>
