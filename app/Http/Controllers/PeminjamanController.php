<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use Carbon\Carbon;
use App\Services\DendaService;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new DendaService())->updateOverdueFines();

        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(15);

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->get();
        return view('peminjaman.create', compact('mahasiswas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'                => 'required|exists:users,id',
            'isbn'                   => 'required|exists:bukus,isbn',
            'tanggal_pinjam'         => 'required|date',
            'tanggal_kembali_rencana'=> 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'isbn.required' => 'ISBN buku wajib diisi.',
            'isbn.exists'   => 'Buku dengan ISBN tersebut tidak ditemukan di database.',
        ]);

        $buku = Buku::where('isbn', $request->isbn)->firstOrFail();

        if ($buku->stok <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['isbn' => 'Stok buku ini sedang habis, tidak bisa dipinjam.']);
        }

        // Simpan transaksi peminjaman
        Peminjaman::create([
            'user_id'                => $request->user_id,
            'buku_id'                => $buku->id,
            'status'                 => 'dipinjam',
            'tanggal_pinjam'         => $request->tanggal_pinjam,
            'tanggal_kembali_rencana'=> $request->tanggal_kembali_rencana,
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil dicatat! Buku: ' . $buku->judul);
    }

    /**
     * Lookup buku berdasarkan ISBN dari database lokal (digunakan AJAX pada form peminjaman).
     */
    public function lookupByIsbn(string $isbn)
    {
        $buku = Buku::where('isbn', $isbn)->first();

        if (!$buku) {
            return response()->json(['message' => 'Buku dengan ISBN ini tidak ditemukan di database.'], 404);
        }

        return response()->json([
            'id'          => $buku->id,
            'isbn'        => $buku->isbn,
            'judul'       => $buku->judul,
            'penulis'     => $buku->penulis,
            'penerbit'    => $buku->penerbit ?? '-',
            'stok'        => $buku->stok,
            'cover_url'   => $buku->cover_url,
        ]);
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $buku = $peminjaman->buku;
        $hariIni = now()->toDateString();

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali_aktual' => $hariIni,
        ]);

        // Kembalikan stok buku
        $buku->increment('stok');

        // Hitung denda menggunakan DendaService
        $dendaService = new DendaService();
        $denda = $dendaService->hitung($peminjaman);

        $pesanSukses = 'Buku berhasil dikembalikan!';

        if ($denda) {
            $pesanSukses .= " Terlambat {$denda->hari_terlambat} hari. Denda yang dikenakan: Rp " . number_format($denda->total_denda, 0, ',', '.') . ".";
        }

        return redirect()->route('peminjaman.index')->with('success', $pesanSukses);
    }

    // Skeleton method sisa resource agar tidak memecah resource routing
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
