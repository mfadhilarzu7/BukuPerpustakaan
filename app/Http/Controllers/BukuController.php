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
        ]);

        // 2. Simpan data ke dalam tabel 'bukus' menggunakan Model Buku
        Buku::create([
            'isbn'    => $request->isbn,
            'judul'   => $request->judul,
            'penulis' => $request->penulis,
            // Jika Anda menambahkan input stok di form, sesuaikan di bawah ini:
            'stok'    => $request->stok ?? 1, 
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
     * Fetch metadata of a book using its ISBN.
     */
    public function fetchISBN($isbn)
    {
        $response = \Illuminate\Support\Facades\Http::get("https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&jscmd=data&format=json");
        
        if ($response->failed()) {
            return response()->json(['message' => 'Failed to fetch data from Open Library'], 500);
        }
        
        $data = $response->json();
        $key = "ISBN:{$isbn}";
        
        if (!isset($data[$key])) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        $bookData = $data[$key];
        $judul = $bookData['title'] ?? '';
        
        $penulisArray = [];
        if (isset($bookData['authors'])) {
            foreach ($bookData['authors'] as $author) {
                $penulisArray[] = $author['name'];
            }
        }
        $penulis = implode(', ', $penulisArray);
        
        $coverUrl = $bookData['cover']['medium'] ?? ($bookData['cover']['large'] ?? null);
        
        return response()->json([
            'judul' => $judul,
            'penulis' => $penulis,
            'cover_url' => $coverUrl
        ]);
    }
}
