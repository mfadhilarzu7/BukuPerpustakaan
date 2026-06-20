<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController; // Import BukuController
use App\Http\Controllers\KatalogController;

// Rute awal bawaan Laravel yang bisa diakses siapa saja
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/', function () {
    return view('welcome');
});

// --------------------------------------------------------
// RUTE KHUSUS MAHASISWA
// --------------------------------------------------------
Route::get('/dashboard-mahasiswa', function () {
    return "Selamat datang di Halaman Mahasiswa!";
})->middleware(['auth', 'role:mahasiswa']);


// --------------------------------------------------------
// RUTE KHUSUS PETUGAS (Menggunakan Group Middleware)
// --------------------------------------------------------
Route::middleware(['auth', 'role:petugas'])->group(function () {
    
    // Halaman Utama/Dashboard Petugas
    Route::get('/dashboard-petugas', [PetugasController::class, 'index']);
    
    // Route API untuk AJAX Auto-fill ISBN (Dimasukkan ke sini agar aman dari akses luar)
    Route::get('/api/isbn/{isbn}', [BukuController::class, 'fetchISBN']);
    
    // Route Resource CRUD Buku (Otomatis mencakup index, create, store, edit, update, destroy)
    Route::resource('buku', BukuController::class);
    
});