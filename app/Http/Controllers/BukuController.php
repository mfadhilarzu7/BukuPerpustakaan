<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::latest()->paginate(20);
        return view('buku.index', compact('bukus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Memanggil file resources/views/buku/create.blade.php
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $request->validate([
            'isbn'    => 'required|unique:bukus,isbn',
            'judul'   => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok'    => 'required|integer|min:0',
        ]);

        // 2. Simpan data ke dalam tabel 'bukus' menggunakan Model Buku
        Buku::create([
            'isbn'         => $request->isbn,
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit ?: null,
            'deskripsi'    => $request->deskripsi,
            'cover_url'    => $request->cover_url,
            'stok'         => $request->stok ?? 1,
        ]);

        // 3. Alihkan halaman kembali dengan pesan sukses
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'isbn'    => 'required|unique:bukus,isbn,' . $buku->id,
            'judul'   => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok'    => 'required|integer|min:0',
        ]);

        $buku->update([
            'isbn'         => $request->isbn,
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'deskripsi'    => $request->deskripsi,
            'stok'         => $request->stok,
        ]);

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Fetch metadata buku via ISBN menggunakan Google Books API.
     */
    public function fetchISBN($isbn)
    {
        $apiKey = config('services.google_books.key');

        $params = ['q' => "isbn:{$isbn}"];
        if ($apiKey) {
            $params['key'] = $apiKey;
        }

        $response = \Illuminate\Support\Facades\Http::get('https://www.googleapis.com/books/v1/volumes', $params);

        if ($response->failed()) {
            return response()->json(['message' => 'Gagal menghubungi Google Books API'], 500);
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $info = $data['items'][0]['volumeInfo'];

        $judul    = $info['title'] ?? '';
        $penulis  = isset($info['authors']) ? implode(', ', $info['authors']) : '';
        $penerbit = $info['publisher'] ?? '';

        // Ambil tahun dari publishedDate (format: YYYY atau YYYY-MM-DD)
        $tahunTerbit = null;
        if (!empty($info['publishedDate'])) {
            $tahunTerbit = substr($info['publishedDate'], 0, 4);
        }

        // Cover: pakai thumbnail, ganti http→https agar tidak diblokir browser
        $coverUrl = null;
        if (!empty($info['imageLinks']['thumbnail'])) {
            $coverUrl = str_replace('http://', 'https://', $info['imageLinks']['thumbnail']);
        } elseif (!empty($info['imageLinks']['smallThumbnail'])) {
            $coverUrl = str_replace('http://', 'https://', $info['imageLinks']['smallThumbnail']);
        }

        $deskripsi = $info['description'] ?? '';

        return response()->json([
            'judul'        => $judul,
            'penulis'      => $penulis,
            'penerbit'     => $penerbit,
            'tahun_terbit' => $tahunTerbit,
            'cover_url'    => $coverUrl,
            'deskripsi'    => $deskripsi,
        ]);
    }
}
