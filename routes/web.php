<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ProfileController;

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
    
    Route::get('/dashboard-mahasiswa', function () {
        return '
            <div style="font-family: sans-serif; padding: 40px; text-align: center;">
                <h2>Selamat datang di Halaman Mahasiswa!</h2>
                <p>Anda saat ini sedang login.</p>
                <br>
                <form method="POST" action="'.route('logout').'">
                    ' . csrf_field() . '
                    <button type="submit" style="background-color: #ef4444; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                        Keluar / Log Out
                    </button>
                </form>
            </div>
        ';
    });
    
});

// --------------------------------------------------------
// RUTE KHUSUS PETUGAS (Menggunakan Group Middleware)
// --------------------------------------------------------
Route::middleware(['auth', 'role:petugas'])->group(function () {
    
    // Halaman Utama/Dashboard Petugas
    Route::get('/dashboard-petugas', [PetugasController::class, 'index']);
    
    // Route API untuk AJAX Auto-fill ISBN
    Route::get('/api/isbn/{isbn}', [BukuController::class, 'fetchISBN']);
    
    // Route Resource CRUD Buku
    Route::resource('buku', BukuController::class);
    
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