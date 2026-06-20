<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetugasController extends Controller
{
    // Tambahkan fungsi index ini untuk mengatur halaman dashboard petugas
    public function index()
    {
        return view('dashboard'); // <--- Ganti sesuai dengan nama file blade view Anda
    }
}