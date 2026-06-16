<?php

/**
 * SEEDER — Database Utama
 *
 * Seeder utama yang memanggil semua seeder lain untuk mengisi
 * data awal aplikasi SIG-Udeung. Dijalankan melalui perintah
 * `php artisan db:seed`.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Jalankan semua seeder untuk data awal sistem.
     *
     * Urutan pemanggilan:
     * 1. AdministratorSeeder  — 3 akun admin default (keuchik, sekdes, operator)
     * 2. PengaturanGampongSeeder — Konfigurasi awal gampong
     * 3. KategoriSuratSeeder     — Jenis-jenis surat desa yang tersedia
     * 4. WilayahPendudukSeeder   — Data wilayah dan contoh penduduk
     */
    public function run(): void
    {
        $this->call([
            AdministratorSeeder::class,
            PengaturanGampongSeeder::class,
            KategoriSuratSeeder::class,
            WilayahPendudukSeeder::class,
        ]);
    }
}
