<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $bukus = Buku::when($request->search, function ($q, $s) {
            $q->where('judul', 'like', "%{$s}%")
              ->orWhere('penulis', 'like', "%{$s}%")
              ->orWhere('isbn', 'like', "%{$s}%");
        })->paginate(12);

        return view('katalog.index', compact('bukus'));
    }
}