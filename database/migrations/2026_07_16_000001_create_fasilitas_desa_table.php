<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas_desa', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_fasilitas', 150);
            $table->string('kategori', 50);
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 200)->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['Aktif', 'Rusak Ringan', 'Rusak Berat', 'Tidak Aktif'])->default('Aktif');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_desa');
    }
};
