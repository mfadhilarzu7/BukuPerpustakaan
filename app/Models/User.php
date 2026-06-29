<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Kolom tambahan untuk peran user
        'nim',  // Kolom tambahan untuk nomor induk mahasiswa
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi (misal ketika diubah ke JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mengatur tipe data (casting) atribut.
     * Di Laravel 11+, pengaturan casts direkomendasikan menggunakan method casts().
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all peminjaman associated with the user.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}