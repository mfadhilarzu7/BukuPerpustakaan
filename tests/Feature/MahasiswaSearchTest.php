<?php

use App\Models\User;
use App\Models\Buku;

test('mahasiswa can search for a newly added book', function () {
    // 1. Create a student
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

    // 2. Admin adds a new book
    $buku = Buku::create([
        'isbn' => '1234567890123',
        'judul' => 'Buku Baru Admin',
        'penulis' => 'Penulis Admin',
        'stok' => 5,
    ]);

    // 3. Mahasiswa searches for the book
    $response = $this->actingAs($mahasiswa)
        ->get(route('dashboard.mahasiswa', ['search' => 'Admin']));

    $response->assertStatus(200);
    $response->assertSee('Buku Baru Admin');
});
