<?php

/**
 * SEEDER — Akun Demo untuk Testing
 *
 * Membuat akun demo yang dapat digunakan untuk menguji sistem:
 * 1. Akun Warga (login.vue) — menggunakan NIK dan No KK
 * 2. Akun Administrator (admin/login) — menggunakan username dan password
 *
 * Data ini akan selalu tersedia meskipun sudah di-deploy ke produksi.
 */

namespace Database\Seeders;

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountSeeder extends Seeder
{
    /**
     * Jalankan seeder — buat akun demo untuk testing.
     *
     * Akun Warga:
     * - NIK    : 1118060512900001
     * - No KK  : 1118060001000001
     * - Nama   : Tgk. Muhammad Rizal
     *
     * Akun Administrator:
     * - Username : admin
     * - Password : password
     * - Role     : keuchik
     */
    public function run(): void
    {
        // ============================================
        // 1. AKUN WARGA DEMO (untuk login.vue)
        // ============================================
        $nikDemo = '1118060512900001';
        $noKkDemo = '1118060001000001';

        // Buat data keluarga demo jika belum ada
        $keluarga = Keluarga::firstOrCreate(
            ['no_kk' => $noKkDemo],
            [
                'alamat' => 'Jl. Utama Gampong Udeung No. 1, Dusun Tunong, Gampong Udeung',
                'dusun' => 'Dusun Tunong',
                'rt_rw' => 'Dusun Tunong',
            ]
        );

        // Buat data penduduk (kepala keluarga) demo jika belum ada
        Penduduk::firstOrCreate(
            ['nik' => $nikDemo],
            [
                'no_kk' => $noKkDemo,
                'nama_lengkap' => 'Tgk. Muhammad Rizal',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Meureudu',
                'tanggal_lahir' => '1990-12-05',
                'agama' => 'Islam',
                'pekerjaan' => 'Petani/Pekebun',
                'pendidikan' => 'SMA',
                'status_perkawinan' => 'Kawin',
                'status_keluarga' => 'Kepala Keluarga',
                'status_mutasi' => 'Tetap',
            ]
        );

        // Update kepala keluarga di tabel keluarga
        $keluarga->update(['kepala_keluarga_nik' => $nikDemo]);

        // Buat data istri demo
        $nikIstri = '1118064508920002';
        Penduduk::firstOrCreate(
            ['nik' => $nikIstri],
            [
                'no_kk' => $noKkDemo,
                'nama_lengkap' => 'Cut Nurhaliza',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Pidie Jaya',
                'tanggal_lahir' => '1992-08-05',
                'agama' => 'Islam',
                'pekerjaan' => 'Mengurus Rumah Tangga',
                'pendidikan' => 'SMA',
                'status_perkawinan' => 'Kawin',
                'status_keluarga' => 'Istri',
                'status_mutasi' => 'Tetap',
            ]
        );

        // Buat data anak demo
        $nikAnak = '1118062010150003';
        Penduduk::firstOrCreate(
            ['nik' => $nikAnak],
            [
                'no_kk' => $noKkDemo,
                'nama_lengkap' => 'Muhammad Fadil',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Pidie Jaya',
                'tanggal_lahir' => '2015-10-15',
                'agama' => 'Islam',
                'pekerjaan' => 'Pelajar/Mahasiswa',
                'pendidikan' => 'SMA',
                'status_perkawinan' => 'Belum Kawin',
                'status_keluarga' => 'Anak',
                'status_mutasi' => 'Tetap',
            ]
        );

        // ============================================
        // 2. AKUN ADMINISTRATOR DEMO (untuk admin/login)
        // ============================================
        Administrator::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'role' => 'keuchik',
            ]
        );

        // Tambahkan akun admin lain untuk testing
        Administrator::firstOrCreate(
            ['username' => 'operator'],
            [
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]
        );
    }
}
