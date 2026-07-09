<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    /**
     * Mengambil data buku dari Google Books API berdasarkan ISBN
     */
    public function fetchByISBN(string $isbn)
    {
        // Menembak URL API Google Books publik
        $response = Http::withoutVerifying()->get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}");

        if ($response->failed() || !isset($response->json()['items'])) {
            return null;
        }

        $bookData = $response->json()['items'][0]['volumeInfo'];

        // Format data yang dikembalikan agar sesuai dengan inputan form kita
        return [
            'judul'     => $bookData['title'] ?? 'Tidak Ada Judul',
            'penulis'   => isset($bookData['authors']) ? implode(', ', $bookData['authors']) : 'Penulis Tidak Diketahui',
            'penerbit'  => $bookData['publisher'] ?? null,
            'cover_url' => $bookData['imageLinks']['thumbnail'] ?? null,
        ];
    }
}