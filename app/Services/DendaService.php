<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Support\Carbon;

class DendaService
{
    const DENDA_PER_HARI = 1000; // Rp 1.000/hari

    public function hitung(Peminjaman $peminjaman): ?Denda
    {
        $kembali = $peminjaman->tanggal_kembali_aktual ?? now();
        $terlambat = Carbon::parse($peminjaman->tanggal_kembali_rencana)->diffInDays($kembali, false);

        if ($terlambat <= 0) {
            return null;
        }

        return Denda::updateOrCreate(
            ['peminjaman_id' => $peminjaman->id],
            [
                'hari_terlambat' => $terlambat,
                'total_denda'    => $terlambat * self::DENDA_PER_HARI,
                'status_bayar'   => 'belum'
            ]
        );
    }
}