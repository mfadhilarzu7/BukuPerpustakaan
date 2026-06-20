<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Perpustakaan</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-gradient-start: #0f172a;
            --bg-gradient-end: #1e1b4b;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #6366f1;
            --accent-hover: #4f46e5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
            color: var(--text-primary);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 50px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(to right, #a5b4fc, #818cf8, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        /* Search Section */
        .search-container {
            max-width: 600px;
            margin: 0 auto 50px auto;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 14px 20px;
            border-radius: 12px;
            border: 1px solid var(--card-border);
            background: rgba(15, 23, 42, 0.6);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .search-button {
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            background: var(--accent-color);
            color: white;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        /* Catalog Grid */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Card Style */
        .book-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .book-card:hover {
            transform: translateY(-6px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.3);
        }

        .book-cover {
            width: 100%;
            height: 280px;
            background: rgba(15, 23, 42, 0.4);
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .book-cover img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-cover img {
            transform: scale(1.05);
        }

        .book-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .book-title {
            font-size: 1.2rem;
            font-weight: 600;
            line-height: 1.4;
            color: var(--text-primary);
        }

        .book-author {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .book-isbn {
            font-size: 0.85rem;
            color: var(--accent-color);
            font-family: monospace;
        }

        .book-stock {
            margin-top: auto;
            padding-top: 15px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stock-badge {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
        }

        .stock-badge.out {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .pagination-links {
            display: flex;
            gap: 8px;
        }

        .pagination-links a, .pagination-links span {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-links a:hover {
            border-color: var(--accent-color);
            background: var(--accent-color);
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: var(--text-secondary);
            font-size: 1.2rem;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>Katalog Buku Perpustakaan</h1>
            <p>Temukan buku pilihan Anda untuk dipelajari hari ini.</p>
        </header>

        <div class="search-container">
            <form action="{{ route('katalog') }}" method="GET" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari berdasarkan judul, penulis, atau ISBN..." 
                    value="{{ request('search') }}"
                    class="search-input"
                >
                <button type="submit" class="search-button">Cari</button>
            </form>
        </div>

        <div class="catalog-grid">
            @forelse($bukus as $buku)
                <div class="book-card">
                    <div class="book-cover">
                        @if($buku->cover_url)
                            <img src="{{ $buku->cover_url }}" alt="Cover {{ $buku->judul }}">
                        @else
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="color: var(--text-secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="book-info">
                        <span class="book-isbn">ISBN: {{ $buku->isbn }}</span>
                        <h2 class="book-title">{{ $buku->judul }}</h2>
                        <span class="book-author">Oleh {{ $buku->penulis }}</span>
                        <div class="book-stock">
                            <span>Stok Tersedia</span>
                            @if($buku->stok > 0)
                                <span class="stock-badge">{{ $buku->stok }} Pcs</span>
                            @else
                                <span class="stock-badge out">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>Maaf, buku yang Anda cari tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        @if($bukus->hasPages())
            <div class="pagination-container">
                <div class="pagination-links">
                    {{ $bukus->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

</body>
</html>
