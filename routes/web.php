<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA (Mengalihkan pengunjung langsung ke halaman login)
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute katalog yang bisa diakses siapa saja
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

// 2. PINTU PENGALIH DASHBOARD (Otomatis membagi user berdasarkan Role)
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    // Jika user adalah petugas, arahkan ke dashboard petugas
    if ($user->role === 'petugas') {
        return redirect('/dashboard-petugas');
    }

    // Jika user adalah mahasiswa, arahkan ke dashboard mahasiswa
    if ($user->role === 'mahasiswa') {
        return redirect('/dashboard-mahasiswa');
    }

    // Jika user tidak memiliki role di atas
    return "Maaf, akun Anda tidak memiliki role yang valid.";
})->middleware(['auth', 'verified'])->name('dashboard');


// --------------------------------------------------------
// RUTE KHUSUS MAHASISWA
// --------------------------------------------------------
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard-mahasiswa', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
    Route::post('/pinjam/{buku}', [MahasiswaController::class, 'pinjam'])->name('mahasiswa.pinjam');
});

// --------------------------------------------------------
// RUTE KHUSUS PETUGAS (Menggunakan Group Middleware)
// --------------------------------------------------------
Route::middleware(['auth', 'role:petugas'])->group(function () {
    
    // Halaman Utama/Dashboard Petugas
    Route::get('/dashboard-petugas', [PetugasController::class, 'index']);
    
    // Route API untuk AJAX Auto-fill ISBN (Google Books)
    Route::get('/api/isbn/{isbn}', [BukuController::class, 'fetchISBN']);

    // Route API untuk lookup buku by ISBN dari database lokal (untuk form peminjaman)
    Route::get('/api/buku-by-isbn/{isbn}', [PeminjamanController::class, 'lookupByIsbn'])->name('peminjaman.lookup-isbn');
    
    // Route Resource CRUD Buku
    Route::resource('buku', BukuController::class);
    
    // Rute Peminjaman & Pengembalian Buku
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    
    // Rute Denda
    Route::get('denda', [DendaController::class, 'index'])->name('denda.index');
    Route::post('denda/{denda}/lunasi', [DendaController::class, 'lunasi'])->name('denda.lunasi');
    
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------------------------
// WAJIB ADA: MENYALAKAN RUTE LOGIN & REGISTER BREEZE
// --------------------------------------------------------
require __DIR__.'/auth.php';