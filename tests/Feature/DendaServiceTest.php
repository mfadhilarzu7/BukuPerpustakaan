<?php

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Services\DendaService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('denda service calculates fine correctly on return', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    $buku = Buku::create([
        'isbn' => '1234567890',
        'judul' => 'Test Buku',
        'penulis' => 'Test Penulis',
        'stok' => 5,
        'penerbit' => 'Test Publisher',
        'tahun_terbit' => 2026
    ]);

    // Create loan that is 3 days overdue
    $loan = Peminjaman::create([
        'user_id' => $mahasiswa->id,
        'buku_id' => $buku->id,
        'status' => 'dipinjam',
        'tanggal_pinjam' => now()->subDays(10)->toDateString(),
        'tanggal_kembali_rencana' => now()->subDays(3)->toDateString(),
    ]);

    $dendaService = new DendaService();
    $denda = $dendaService->hitung($loan);

    expect($denda)->not->toBeNull()
        ->and((int)$denda->hari_terlambat)->toBe(3)
        ->and((int)$denda->total_denda)->toBe(15000)
        ->and($denda->status_bayar)->toBe('belum');
});

test('updateOverdueFines calculates and stores fine for overdue active loans automatically', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    $buku = Buku::create([
        'isbn' => '0987654321',
        'judul' => 'Test Buku 2',
        'penulis' => 'Test Penulis 2',
        'stok' => 5,
        'penerbit' => 'Test Publisher 2',
        'tahun_terbit' => 2026
    ]);

    // Create loan that is 5 days overdue
    $loan = Peminjaman::create([
        'user_id' => $mahasiswa->id,
        'buku_id' => $buku->id,
        'status' => 'dipinjam',
        'tanggal_pinjam' => now()->subDays(10)->toDateString(),
        'tanggal_kembali_rencana' => now()->subDays(5)->toDateString(),
    ]);

    // Ensure no Denda record exists yet
    expect(Denda::where('peminjaman_id', $loan->id)->exists())->toBeFalse();

    // Trigger automatic updates
    (new DendaService())->updateOverdueFines();

    // Denda record should have been created
    $denda = Denda::where('peminjaman_id', $loan->id)->first();
    expect($denda)->not->toBeNull()
        ->and((int)$denda->hari_terlambat)->toBe(5)
        ->and((int)$denda->total_denda)->toBe(25000)
        ->and($denda->status_bayar)->toBe('belum');
});

test('student dashboard renders and updates fines', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa', 'nim' => '12345']);
    $buku = Buku::create([
        'isbn' => '5555555555',
        'judul' => 'Unique Title For Search',
        'penulis' => 'Test Penulis 3',
        'stok' => 3,
        'penerbit' => 'Test Publisher 3',
        'tahun_terbit' => 2026
    ]);

    // Create loan that is 2 days overdue
    $loan = Peminjaman::create([
        'user_id' => $mahasiswa->id,
        'buku_id' => $buku->id,
        'status' => 'dipinjam',
        'tanggal_pinjam' => now()->subDays(5)->toDateString(),
        'tanggal_kembali_rencana' => now()->subDays(2)->toDateString(),
    ]);

    $response = $this->actingAs($mahasiswa)
        ->get('/dashboard-mahasiswa?search=Unique');

    $response->assertStatus(200);
    $response->assertSee('Unique Title For Search');
    $response->assertSee('Terlambat 2 Hari');
    $response->assertSee('Rp 10.000');
});
