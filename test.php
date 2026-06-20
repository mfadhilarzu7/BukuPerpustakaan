<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = app(App\Http\Controllers\PeminjamanController::class);
    $peminjaman = App\Models\Peminjaman::find(2);
    if (!$peminjaman) {
        echo "Peminjaman 2 not found.\n";
        exit;
    }
    echo "Status before: " . $peminjaman->status . "\n";
    $result = $controller->kembalikan($peminjaman);
    echo "Controller executed successfully.\n";
    
    $peminjaman->refresh();
    echo "Status after: " . $peminjaman->status . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
