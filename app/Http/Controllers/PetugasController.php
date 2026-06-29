<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Services\DendaService;

class PetugasController extends Controller
{
    public function index()
    {
        (new DendaService())->updateOverdueFines();

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

    public function exportLaporan()
    {
        $fileName = 'laporan_peminjaman_' . date('Y-m-d') . '.csv';
        $peminjamans = Peminjaman::with(['user', 'buku', 'denda'])->orderBy('created_at', 'desc')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'Peminjam', 'Buku', 'Tanggal Pinjam', 'Tenggat', 'Tanggal Kembali', 'Status', 'Denda (Rp)'];

        $callback = function() use($peminjamans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($peminjamans as $p) {
                $denda = $p->denda ? $p->denda->total_denda : 0;
                $row = [
                    $p->id,
                    $p->user ? $p->user->name : '-',
                    $p->buku ? $p->buku->judul : '-',
                    $p->tanggal_pinjam,
                    $p->tanggal_kembali_rencana,
                    $p->tanggal_kembali_aktual ?? '-',
                    $p->status,
                    $denda
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}