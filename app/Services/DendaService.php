<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Support\Carbon;

class DendaService
{
    const DENDA_PER_HARI = 5000; // Rp 5.000/hari

    /**
     * Hitung atau perbarui denda untuk peminjaman tertentu.
     */
    public function hitung(Peminjaman $peminjaman): ?Denda
    {
        $kembali = $peminjaman->tanggal_kembali_aktual ?? now()->toDateString();
        $rencana = Carbon::parse($peminjaman->tanggal_kembali_rencana);
        $aktual = Carbon::parse($kembali);
        $terlambat = $rencana->diffInDays($aktual, false);

        if ($terlambat <= 0) {
            return null;
        }

        $denda = Denda::where('peminjaman_id', $peminjaman->id)->first();

        // Jika denda sudah ditandai lunas, tapi keterlambatan bertambah
        if ($denda && $denda->status_bayar === 'lunas') {
            if ($terlambat > $denda->hari_terlambat) {
                $denda->update([
                    'hari_terlambat' => $terlambat,
                    'total_denda'    => $terlambat * self::DENDA_PER_HARI,
                    'status_bayar'   => 'belum', // Menjadi belum lunas untuk selisih hari baru
                ]);
            }
            return $denda;
        }

        return Denda::updateOrCreate(
            ['peminjaman_id' => $peminjaman->id],
            [
                'hari_terlambat' => $terlambat,
                'total_denda'    => $terlambat * self::DENDA_PER_HARI,
                'status_bayar'   => $denda ? $denda->status_bayar : 'belum'
            ]
        );
    }

    /**
     * Cari semua transaksi aktif (belum dikembalikan) yang melewati batas rencana kembali,
     * lalu hitung/buat/perbarui dendanya secara otomatis.
     */
    public function updateOverdueFines(): void
    {
        $today = now()->toDateString();
        $overdueLoans = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', $today)
            ->get();

        foreach ($overdueLoans as $loan) {
            $this->hitung($loan);
        }
    }
}