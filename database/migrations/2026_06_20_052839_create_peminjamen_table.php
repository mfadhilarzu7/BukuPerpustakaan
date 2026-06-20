<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            // Membuat kolom foreign key untuk user dan buku
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buku_id')->constrained()->onDelete('cascade');
            
            // Kolom pelengkap sesuai logika seeder Anda
            $table->string('status'); // dipinjam, dikembalikan, terlambat
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable(); // boleh null jika belum dikembalikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};