<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nomor_registrasi', 30)->unique();
            $table->string('nik_pemohon', 16);
            $table->ulid('kategori_surat_id');
            $table->json('data_isian');
            $table->json('file_syarat');
            $table->enum('status', ['Pending', 'Diproses', 'Disetujui', 'Ditolak', 'Selesai'])->default('Pending');
            $table->text('catatan_penolakan')->nullable();
            $table->string('qr_hash', 64)->nullable()->unique();
            $table->string('file_pdf_url')->nullable();
            $table->ulid('diverifikasi_oleh')->nullable();
            $table->timestamps();
            
            $table->foreign('nik_pemohon')
                ->references('nik')
                ->on('penduduk')
                ->cascadeOnDelete();
            
            $table->foreign('kategori_surat_id')
                ->references('id')
                ->on('kategori_surat')
                ->restrictOnDelete();
            
            $table->foreign('diverifikasi_oleh')
                ->references('id')
                ->on('administrators')
                ->nullOnDelete();
            
            $table->index('status');
            $table->index('nik_pemohon');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
