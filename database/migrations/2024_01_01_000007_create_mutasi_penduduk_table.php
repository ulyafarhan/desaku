<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->enum('jenis_mutasi', ['Kelahiran', 'Kematian', 'Kedatangan', 'Kepindahan']);
            $table->date('tanggal_mutasi');
            $table->text('keterangan');
            $table->string('dokumen_bukti');
            $table->enum('status_verifikasi', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('nik')
                ->references('nik')
                ->on('penduduk')
                ->cascadeOnDelete();
            
            $table->foreign('diverifikasi_oleh')
                ->references('id')
                ->on('administrators')
                ->nullOnDelete();
            
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_penduduk');
    }
};
