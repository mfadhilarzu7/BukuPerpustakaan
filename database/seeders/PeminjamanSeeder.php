<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User Petugas (agar bisa login ke dashboard)
        User::firstOrCreate(
            ['email' => 'admin@perpustakaan.test'],
            [
                'name' => 'Admin Perpus',
                'password' => bcrypt('password'),
                'role' => 'petugas',
            ]
        );

        // 2. Buat User Mahasiswa
        $mhs1 = User::firstOrCreate(
            ['email' => 'rina@perpustakaan.test'],
            ['name' => 'Rina Safitri', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'nim' => '20260001']
        );
        $mhs2 = User::firstOrCreate(
            ['email' => 'budi@perpustakaan.test'],
            ['name' => 'Budi Santoso', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'nim' => '20260002']
        );
        $mhs3 = User::firstOrCreate(
            ['email' => 'dina@perpustakaan.test'],
            ['name' => 'Dina Kurnia', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'nim' => '20260003']
        );
        $mhs4 = User::firstOrCreate(
            ['email' => 'hendra@perpustakaan.test'],
            ['name' => 'Hendra P.', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'nim' => '20260004']
        );
        $mhs5 = User::firstOrCreate(
            ['email' => 'siti@perpustakaan.test'],
            ['name' => 'Siti Rahma', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'nim' => '20260005']
        );

        // 3. Buat Buku
        $buku1 = Buku::firstOrCreate(
            ['isbn' => '9786026232242'],
            ['judul' => 'Algoritma & Pemrograman', 'penulis' => 'Rinaldi Munir', 'stok' => 23, 'penerbit' => 'Informatika', 'tahun_terbit' => 2021]
        );
        $buku2 = Buku::firstOrCreate(
            ['isbn' => '9789791153164'],
            ['judul' => 'Basis Data Lanjutan', 'penulis' => 'Fathansyah', 'stok' => 18, 'penerbit' => 'Informatika', 'tahun_terbit' => 2019]
        );
        $buku3 = Buku::firstOrCreate(
            ['isbn' => '9780132126953'],
            ['judul' => 'Jaringan Komputer', 'penulis' => 'Andrew Tanenbaum', 'stok' => 14, 'penerbit' => 'Pearson', 'tahun_terbit' => 2020]
        );
        $buku4 = Buku::firstOrCreate(
            ['isbn' => '9789797418734'],
            ['judul' => 'Kalkulus Teknik', 'penulis' => 'K.A. Stroud', 'stok' => 10, 'penerbit' => 'Erlangga', 'tahun_terbit' => 2018]
        );
        $buku5 = Buku::firstOrCreate(
            ['isbn' => '9786021514917'],
            ['judul' => 'Sistem Operasi', 'penulis' => 'Hariyanto', 'stok' => 8, 'penerbit' => 'Informatika', 'tahun_terbit' => 2022]
        );

        // 4. Buat Peminjaman
        // Transaksi 1: Rina Safitri - Algoritma & Pemrograman (Status: Aktif, Tanggal Kembali Rencana: Hari ini + 5 hari)
        Peminjaman::create([
            'user_id' => $mhs1->id,
            'buku_id' => $buku1->id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->subDays(2)->toDateString(),
            'tanggal_kembali_rencana' => now()->addDays(5)->toDateString(),
        ]);

        // Transaksi 2: Budi Santoso - Basis Data Lanjutan (Status: Terlambat, Tanggal Kembali Rencana: Hari ini - 3 hari)
        $p2 = Peminjaman::create([
            'user_id' => $mhs2->id,
            'buku_id' => $buku2->id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_kembali_rencana' => now()->subDays(3)->toDateString(),
        ]);
        Denda::create([
            'peminjaman_id' => $p2->id,
            'hari_terlambat' => 3,
            'total_denda' => 15000,
            'status_bayar' => 'belum',
        ]);

        // Transaksi 3: Dina Kurnia - Jaringan Komputer (Status: Aktif, Tanggal Kembali Rencana: Hari ini + 7 hari)
        Peminjaman::create([
            'user_id' => $mhs3->id,
            'buku_id' => $buku3->id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->subDays(1)->toDateString(),
            'tanggal_kembali_rencana' => now()->addDays(7)->toDateString(),
        ]);

        // Transaksi 4: Hendra P. - Kalkulus Teknik (Status: Hampir jatuh tempo, Tanggal Kembali Rencana: Hari ini + 1 hari)
        Peminjaman::create([
            'user_id' => $mhs4->id,
            'buku_id' => $buku4->id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->subDays(6)->toDateString(),
            'tanggal_kembali_rencana' => now()->addDays(1)->toDateString(),
        ]);

        // Transaksi 5: Siti Rahma - Sistem Operasi (Status: Terlambat, Tanggal Kembali Rencana: Hari ini - 5 hari)
        $p5 = Peminjaman::create([
            'user_id' => $mhs5->id,
            'buku_id' => $buku5->id,
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()->subDays(12)->toDateString(),
            'tanggal_kembali_rencana' => now()->subDays(5)->toDateString(),
        ]);
        Denda::create([
            'peminjaman_id' => $p5->id,
            'hari_terlambat' => 5,
            'total_denda' => 25000,
            'status_bayar' => 'belum',
        ]);

        // Tambah peminjaman simulasi lama untuk menghitung kepopuleran buku
        for ($i = 0; $i < 5; $i++) {
            Peminjaman::create([
                'user_id' => $mhs1->id,
                'buku_id' => $buku1->id,
                'status' => 'dikembalikan',
                'tanggal_pinjam' => now()->subMonths(1)->toDateString(),
                'tanggal_kembali_rencana' => now()->subMonths(1)->addDays(7)->toDateString(),
                'tanggal_kembali_aktual' => now()->subMonths(1)->addDays(5)->toDateString(),
            ]);
            Peminjaman::create([
                'user_id' => $mhs2->id,
                'buku_id' => $buku2->id,
                'status' => 'dikembalikan',
                'tanggal_pinjam' => now()->subMonths(1)->toDateString(),
                'tanggal_kembali_rencana' => now()->subMonths(1)->addDays(7)->toDateString(),
                'tanggal_kembali_aktual' => now()->subMonths(1)->addDays(5)->toDateString(),
            ]);
        }
    }
}
