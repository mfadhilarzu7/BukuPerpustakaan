<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div id="flash-success" class="flex items-center gap-3 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                    <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-700">✕</button>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="flex items-center gap-3 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H2.645c-1.73 0-2.813-1.874-1.948-3.374l8.163-14.126c.866-1.5 3.032-1.5 3.898 0l8.163 14.126zm-10.551 5.625h.008v.008h-.008v-.008z"/></svg>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                    <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-400 hover:text-rose-700">✕</button>
                </div>
            @endif
            
            <!-- Welcome and Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-violet-700 rounded-2xl p-6 text-white shadow-xl flex flex-col justify-between">
                    <div>
                        <span class="bg-white/20 text-white text-xs px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider">Profil Mahasiswa</span>
                        <h3 class="text-2xl font-bold mt-4">{{ Auth::user()->name }}</h3>
                        <p class="text-indigo-100 mt-1 font-medium">NIM: {{ Auth::user()->nim ?? '-' }}</p>
                        <p class="text-indigo-200 text-sm mt-1">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="mt-6 flex items-center gap-2 text-indigo-100 text-sm">
                        <svg class="w-5 height-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                        <span>Status Akun Aktif</span>
                    </div>
                </div>

                <!-- Active Loans Stat -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase">Sedang Dipinjam</p>
                        <h4 class="text-4xl font-extrabold text-slate-800">{{ $peminjamanAktif->count() }} <span class="text-lg font-normal text-slate-500">Buku</span></h4>
                        <p class="text-xs text-slate-400">Harap kembalikan buku sebelum batas waktu.</p>
                    </div>
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                </div>

                <!-- Total Unpaid Fines Stat -->
                @php
                    $totalUnpaidFine = $peminjamanAktif->sum(function($p) {
                        return $p->denda && $p->denda->status_bayar === 'belum' ? $p->denda->total_denda : 0;
                    });
                @endphp
                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase">Total Denda Belum Bayar</p>
                        <h4 class="text-3xl font-extrabold text-rose-600">Rp {{ number_format($totalUnpaidFine, 0, ',', '.') }}</h4>
                        <p class="text-xs text-slate-400">Tarif keterlambatan: Rp 5.000 / hari.</p>
                    </div>
                    <div class="p-4 bg-rose-50 text-rose-600 rounded-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Warning Alert for Fines -->
            @if($totalUnpaidFine > 0)
                <div class="flex items-center gap-3 p-4 bg-rose-50 border-l-4 border-rose-600 text-rose-800 rounded-r-xl shadow-sm">
                    <svg class="w-6 h-6 shrink-0 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Peringatan Keterlambatan!</p>
                        <p class="text-xs text-rose-700 mt-0.5">Anda memiliki denda keterlambatan buku yang belum dibayar. Harap segera kembalikan buku dan selesaikan pembayaran denda di meja petugas perpustakaan.</p>
                    </div>
                </div>
            @endif

            <!-- Active Loans Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full"></span>
                        Buku yang Sedang Anda Pinjam
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">Batas peminjaman sesuai ketentuan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                                <th class="px-6 py-4">Buku</th>
                                <th class="px-6 py-4">Tanggal Pinjam</th>
                                <th class="px-6 py-4">Batas Kembali</th>
                                <th class="px-6 py-4">Status & Keterlambatan</th>
                                <th class="px-6 py-4">Akumulasi Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse($peminjamanAktif as $loan)
                                @php
                                    $isOverdue = false;
                                    $lateDays = 0;
                                    $fineAmount = 0;
                                    
                                    if ($loan->denda) {
                                        $isOverdue = true;
                                        $lateDays = $loan->denda->hari_terlambat;
                                        $fineAmount = $loan->denda->total_denda;
                                    } else {
                                        $rencana = \Carbon\Carbon::parse($loan->tanggal_kembali_rencana);
                                        if ($rencana->isPast()) {
                                            $isOverdue = true;
                                            $lateDays = $rencana->diffInDays(now(), false);
                                            $fineAmount = $lateDays * 5000;
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/55 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $loan->buku->judul }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">ISBN: {{ $loan->buku->isbn }} | Oleh {{ $loan->buku->penulis }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                                Terlambat {{ $lateDays }} Hari
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                Aktif / Aman
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        @if($fineAmount > 0)
                                            <span class="text-rose-600">Rp {{ number_format($fineAmount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                            <span class="text-sm font-medium">Anda tidak memiliki peminjaman aktif.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Two-Column Section: History & Catalog -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Catalog Section (Left / Takes 2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full"></span>
                                    Katalog Buku Perpustakaan
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Cari dan jelajahi ketersediaan buku secara langsung.</p>
                            </div>
                            
                            <!-- Search Form -->
                            <form action="{{ route('dashboard.mahasiswa') }}" method="GET" class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Cari judul, penulis..." 
                                    value="{{ $search }}" 
                                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full sm:w-60"
                                >
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition">
                                    Cari
                                </button>
                                @if($search)
                                    <a href="{{ route('dashboard.mahasiswa') }}" class="border border-slate-200 text-slate-500 hover:bg-slate-50 px-3 py-2 rounded-xl text-sm flex items-center justify-center">
                                        Reset
                                    </a>
                                @endif
                            </form>
                        </div>

                        <!-- Catalog Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            @forelse($bukus as $buku)
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex gap-4 hover:shadow-md hover:border-slate-200 transition">
                                    <div class="w-20 h-28 bg-white border border-slate-100 rounded-lg flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
                                        @if($buku->cover_url)
                                            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="object-cover w-full h-full">
                                        @else
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-between py-1 flex-grow">
                                        <div>
                                            <h4 class="font-bold text-slate-800 line-clamp-1 text-sm sm:text-base">{{ $buku->judul }}</h4>
                                            <p class="text-xs text-slate-400 mt-0.5">Penulis: {{ $buku->penulis }}</p>
                                            <p class="text-xs text-slate-400">ISBN: {{ $buku->isbn }}</p>
                                        </div>
                                        <div class="flex items-center justify-between mt-3 gap-2">
                                            @if($buku->stok > 0)
                                                <span class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs px-2.5 py-0.5 rounded-full font-bold">
                                                    Tersedia: {{ $buku->stok }} Pcs
                                                </span>
                                                @if(in_array($buku->id, $bukuSedangDipinjamIds))
                                                    <span class="text-xs text-slate-400 italic">Sudah dipinjam</span>
                                                @else
                                                    <button
                                                        type="button"
                                                        onclick="bukaPinjamModal({{ $buku->id }}, '{{ addslashes($buku->judul) }}')"
                                                        class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150"
                                                    >
                                                        + Pinjam
                                                    </button>
                                                @endif
                                            @else
                                                <span class="bg-rose-50 border border-rose-100 text-rose-700 text-xs px-2.5 py-0.5 rounded-full font-bold">
                                                    Habis Dipinjam
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center text-slate-400 py-12">
                                    Tidak ada buku ditemukan.
                                </div>
                            @endforelse
                        </div>

                        <!-- Catalog Pagination -->
                        <div class="mt-6 border-t border-slate-100 pt-4">
                            {{ $bukus->appends(['search' => $search, 'riwayat_page' => request('riwayat_page')])->links() }}
                        </div>
                    </div>
                </div>

                <!-- History Section (Right / Takes 1 Col) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col h-full">
                        <div class="pb-5 border-b border-slate-100">
                            <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full"></span>
                                Riwayat Peminjaman
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">Daftar buku yang sudah dikembalikan.</p>
                        </div>

                        <div class="divide-y divide-slate-100 flex-grow mt-4">
                            @forelse($riwayatPeminjaman as $history)
                                <div class="py-3.5 space-y-2">
                                    <div>
                                        <h4 class="font-semibold text-slate-800 line-clamp-1">{{ $history->buku->judul }}</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">Dikembalikan pada: {{ \Carbon\Carbon::parse($history->tanggal_kembali_aktual)->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($history->denda)
                                            @if($history->denda->status_bayar === 'lunas')
                                                <span class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                                                    Denda Lunas
                                                </span>
                                            @else
                                                <span class="bg-rose-50 border border-rose-100 text-rose-700 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                                                    Denda: Rp {{ number_format($history->denda->total_denda, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="bg-slate-50 border border-slate-200 text-slate-500 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                                                Tanpa Denda / Tepat Waktu
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-slate-400 py-12">
                                    Belum ada riwayat pengembalian buku.
                                </div>
                            @endforelse
                        </div>

                        <!-- History Pagination -->
                        <div class="mt-6 border-t border-slate-100 pt-4">
                            {{ $riwayatPeminjaman->appends(['search' => $search, 'katalog_page' => request('katalog_page')])->links() }}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- ============================================================
         MODAL KONFIRMASI PINJAM BUKU
    ============================================================ --}}
    <div id="modal-pinjam" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="tutupPinjamModal()"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 z-10 animate-fadeIn">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Konfirmasi Peminjaman</h3>
                    <p class="text-sm text-slate-500 mt-1">Isi tanggal pengembalian untuk melanjutkan</p>
                </div>
                <button onclick="tutupPinjamModal()" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>

            {{-- Info buku --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3 mb-5">
                <p class="text-xs text-indigo-500 font-semibold uppercase tracking-wider">Buku yang dipinjam</p>
                <p class="font-bold text-slate-800 mt-1" id="modal-judul-buku">—</p>
                <p class="text-xs text-slate-500 mt-0.5">Tanggal pinjam: <span class="font-semibold">{{ now()->translatedFormat('d F Y') }}</span></p>
            </div>

            <form id="form-pinjam" method="POST" action="">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="modal-tgl-kembali">
                        Tanggal Pengembalian <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="modal-tgl-kembali"
                        name="tanggal_kembali_rencana"
                        required
                        min="{{ now()->addDay()->toDateString() }}"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        value="{{ now()->addDays(7)->toDateString() }}"
                    >
                    <p class="text-xs text-slate-400 mt-1.5">Keterlambatan akan dikenai denda Rp 5.000 / hari.</p>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-colors text-sm"
                    >
                        ✔ Pinjam Sekarang
                    </button>
                    <button
                        type="button"
                        onclick="tutupPinjamModal()"
                        class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold py-3 rounded-xl transition-colors text-sm"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes fadeIn { from { opacity:0; transform:scale(0.97); } to { opacity:1; transform:scale(1); } }
        .animate-fadeIn { animation: fadeIn 0.2s ease; }
    </style>

    <script>
        function bukaPinjamModal(bukuId, judulBuku) {
            document.getElementById('modal-judul-buku').textContent = judulBuku;
            document.getElementById('form-pinjam').action = '/pinjam/' + bukuId;
            document.getElementById('modal-pinjam').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function tutupPinjamModal() {
            document.getElementById('modal-pinjam').classList.add('hidden');
            document.body.style.overflow = '';
        }
        // Tutup modal dengan Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') tutupPinjamModal();
        });
    </script>

</x-app-layout>
