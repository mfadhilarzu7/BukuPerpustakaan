<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Petugas
        User::factory()->create([
            'name' => 'Test Petugas',
            'email' => 'petugas@example.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
        ]);

        // Akun Mahasiswa
        User::factory()->create([
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
        ]);
    }
}