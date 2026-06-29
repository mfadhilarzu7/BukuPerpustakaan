<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PengembalianTest extends TestCase
{
    public function test_kembalikan()
    {
        $petugas = User::where('role', 'petugas')->first();
        $peminjaman = Peminjaman::where('status', 'dipinjam')->first();
        if(!$peminjaman) {
            echo "No active loans.\n";
            return;
        }

        $response = $this->actingAs($petugas)->post("/peminjaman/{$peminjaman->id}/kembalikan");
        echo "Status: " . $response->status() . "\n";
        if($response->status() == 302) {
            echo "Redirected to: " . $response->headers->get('Location') . "\n";
            echo "Session success: " . session('success') . "\n";
            echo "Session error: " . session('error') . "\n";
        } else {
            echo "Response: " . $response->getContent() . "\n";
        }
    }
}
