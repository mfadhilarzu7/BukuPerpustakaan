<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Services\DendaService;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Otomatis hitung dan update denda keterlambatan buku
        (new DendaService())->updateOverdueFines();

        // 2. Ambil peminjaman aktif milik mahasiswa ini
        $peminjamanAktif = Peminjaman::with(['buku', 'denda'])
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->get();

        // 3. Ambil riwayat peminjaman mahasiswa ini (yang statusnya dikembalikan)
        $riwayatPeminjaman = Peminjaman::with(['buku', 'denda'])
            ->where('user_id', $user->id)
            ->where('status', 'dikembalikan')
            ->latest()
            ->paginate(5, ['*'], 'riwayat_page');

        // 4. Ambil katalog buku untuk pencarian
        $search = $request->input('search');
        $bukus = Buku::when($search, function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('penulis', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        })->latest()->paginate(6, ['*'], 'katalog_page');

        // 5. Ambil ID buku yang sedang aktif dipinjam mahasiswa ini (untuk disable tombol)
        $bukuSedangDipinjamIds = $peminjamanAktif->pluck('buku_id')->toArray();

        return view('dashboard-mahasiswa', compact(
            'peminjamanAktif',
            'riwayatPeminjaman',
            'bukus',
            'search',
            'bukuSedangDipinjamIds'
        ));
    }

    /**
     * Proses peminjaman buku langsung oleh mahasiswa dari katalog.
     */
    public function pinjam(Request $request, Buku $buku)
    {
        $request->validate([
            'tanggal_kembali_rencana' => 'required|date|after:today',
        ], [
            'tanggal_kembali_rencana.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_kembali_rencana.after'    => 'Tanggal pengembalian harus setelah hari ini.',
        ]);

        $user = auth()->user();

        // Cek apakah mahasiswa sudah meminjam buku ini dan belum mengembalikan
        $sudahMeminjam = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahMeminjam) {
            return redirect()->route('dashboard.mahasiswa')
                ->with('error', 'Anda sudah meminjam buku "' . $buku->judul . '" dan belum mengembalikannya.');
        }

        // Cek stok
        if ($buku->stok <= 0) {
            return redirect()->route('dashboard.mahasiswa')
                ->with('error', 'Maaf, stok buku "' . $buku->judul . '" sedang habis.');
        }

        // Buat transaksi peminjaman
        Peminjaman::create([
            'user_id'                => $user->id,
            'buku_id'                => $buku->id,
            'status'                 => 'dipinjam',
            'tanggal_pinjam'         => now()->toDateString(),
            'tanggal_kembali_rencana'=> $request->tanggal_kembali_rencana,
        ]);

        // Kurangi stok
        $buku->decrement('stok');

        return redirect()->route('dashboard.mahasiswa')
            ->with('success', 'Berhasil meminjam buku "' . $buku->judul . '"! Harap kembalikan sebelum ' . \Carbon\Carbon::parse($request->tanggal_kembali_rencana)->translatedFormat('d F Y') . '.');
    }
}
