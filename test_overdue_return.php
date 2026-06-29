<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Peminjaman::where('status', 'dipinjam')->first();
if(!$p){
    // create one
    echo "Creating a loan\n";
    $p = App\Models\Peminjaman::create([
        'user_id' => 1,
        'buku_id' => 1,
        'status' => 'dipinjam',
        'tanggal_pinjam' => '2026-06-01',
        'tanggal_kembali_rencana' => '2026-06-10'
    ]);
} else {
    $p->update(['tanggal_kembali_rencana' => '2026-06-10']);
}

echo 'Returning '.$p->id."\n";
try {
    $c = new App\Http\Controllers\PeminjamanController();
    $c->kembalikan($p);
    echo ' Success';
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
