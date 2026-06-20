<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class PetugasController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::sum('stok');
        $uniqueBukuCount = Buku::count();
        $dipinjamCount = Peminjaman::where('status', 'dipinjam')->count();
        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
        
        $terlambatCount = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->count();
            
        $totalDenda = Denda::where('status_bayar', 'belum')->sum('total_denda');
        
        $transaksiTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();
            
        $bukuPopuler = Buku::withCount('peminjamans')
            ->orderBy('peminjamans_count', 'desc')
            ->take(3)
            ->get();

        return view('dashboard-petugas', compact(
            'totalBuku',
            'uniqueBukuCount',
            'dipinjamCount',
            'totalMahasiswa',
            'terlambatCount',
            'totalDenda',
            'transaksiTerbaru',
            'bukuPopuler'
        ));
    }
}