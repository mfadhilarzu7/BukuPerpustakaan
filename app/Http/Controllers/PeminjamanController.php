<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $bukus = Buku::where('stok', '>', 0)->get();

        return view('peminjaman.create', compact('mahasiswas', 'bukus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['buku_id' => 'Stok buku ini sedang habis.']);
        }

        // Simpan transaksi peminjaman
        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }

    /**
     * Process returning a borrowed book.
     */
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

        // Hitung keterlambatan dan denda
        $rencanaKembali = Carbon::parse($peminjaman->tanggal_kembali_rencana);
        $aktualKembali = Carbon::parse($hariIni);
        $selisihHari = $rencanaKembali->diffInDays($aktualKembali, false);

        $pesanSukses = 'Buku berhasil dikembalikan!';

        if ($selisihHari > 0) {
            $tarifDendaPerHari = 5000;
            $totalDenda = $selisihHari * $tarifDendaPerHari;

            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'hari_terlambat' => $selisihHari,
                'total_denda' => $totalDenda,
                'status_bayar' => 'belum',
            ]);

            $pesanSukses .= " Terlambat {$selisihHari} hari. Denda yang dikenakan: Rp " . number_format($totalDenda, 0, ',', '.') . ".";
        }

        return redirect()->route('peminjaman.index')->with('success', $pesanSukses);
    }

    // Skeleton method sisa resource agar tidak memecah resource routing
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
