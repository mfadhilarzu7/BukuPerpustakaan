<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;

class Denda extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'hari_terlambat',
        'total_denda',
        'status_bayar',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
