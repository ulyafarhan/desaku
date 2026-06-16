<?php

/**
 * MIGRASI — Tabel Pengaturan Gampong
 *
 * Membuat tabel `pengaturan_gampong` untuk menyimpan konfigurasi
 * dan pengaturan dinamis aplikasi SIG-Udeung. Setiap pengaturan
 * disimpan sebagai pasangan kunci-nilai (key-value) dengan dukungan
 * berbagai tipe data.
 *
 * @see \App\Models\PengaturanGampong
 * @see \App\Filament\Pages\PengaturanSistem
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi — buat tabel `pengaturan_gampong`.
     *
     * Struktur tabel:
     * - id         : ULID (Universal Unique Lexicographically Sortable Identifier) sebagai primary key
     * - kunci      : Nama unik pengaturan, maksimal 50 karakter (contoh: 'nama_gampong', 'kode_pos', 'batas_wilayah')
     * - nilai      : Nilai pengaturan, menggunakan tipe TEXT untuk fleksibilitas menyimpan data apapun
     * - tipe_data  : Tipe data nilai ('string', 'integer', 'boolean', 'json', 'float'), default 'string'
     * - deskripsi  : Penjelasan singkat tentang pengaturan ini (opsional, nullable)
     * - updated_at : Waktu terakhir pengaturan diperbarui (otomatis)
     *
     * Index: kunci (untuk pencarian cepat berdasarkan nama pengaturan)
     */
    public function up(): void
    {
        Schema::create('pengaturan_gampong', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kunci', 50)->unique();
            $table->text('nilai');
            $table->string('tipe_data', 20)->default('string');
            $table->string('deskripsi')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('kunci');
        });
    }

    /**
     * Balikkan migrasi — hapus tabel `pengaturan_gampong`.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gampong');
    }
};
