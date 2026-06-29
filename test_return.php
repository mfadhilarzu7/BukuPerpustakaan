<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Peminjaman::where('status', 'dipinjam')->first();
if(!$p){
    echo 'No active loan';
    exit;
}
echo 'Returning '.$p->id."\n";
try {
    $c = new App\Http\Controllers\PeminjamanController();
    $c->kembalikan($p);
    echo ' Success';
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
