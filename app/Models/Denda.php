<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'hari_terlambat',
        'total_denda',
        'status_bayar',
    ];
}
