<?php

use Illuminate\Support\Facades\Route;
// Import Controller yang akan digunakan
use App\Http\Controllers\PetugasController;

// Rute awal bawaan Laravel yang bisa diakses siapa saja
Route::get('/', function () {
    return view('welcome');
});

// --------------------------------------------------------
// RUTE DENGAN MIDDLEWARE ROLE
// --------------------------------------------------------

// Rute khusus 'petugas' menggunakan PetugasController
// Hanya bisa diakses jika user sudah login dan memiliki role 'petugas'
Route::get('/dashboard-petugas', [PetugasController::class, 'index'])->middleware(['auth', 'role:petugas']);

// Rute khusus 'mahasiswa' (sebagai contoh jika masih menggunakan fungsi langsung)
// Hanya bisa diakses jika user sudah login dan memiliki role 'mahasiswa'
Route::get('/dashboard-mahasiswa', function () {
    return "Selamat datang di Halaman Mahasiswa!";
})->middleware(['auth', 'role:mahasiswa']);