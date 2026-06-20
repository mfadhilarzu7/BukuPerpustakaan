<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'isbn',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'deskripsi',
        'cover_url',
        'stok',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
