<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->string('nik', 16)->primary();
            $table->string('no_kk', 16);
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 20);
            $table->string('pendidikan', 50);
            $table->string('pekerjaan', 50);
            $table->string('status_perkawinan', 20);
            $table->string('status_keluarga', 30);
            $table->enum('status_mutasi', ['Tetap', 'Pindah', 'Meninggal'])->default('Tetap');
            $table->string('telegram_chat_id', 50)->nullable()->unique();
            $table->timestamps();
            
            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('keluarga')
                ->restrictOnDelete();
            
            $table->index('nama_lengkap');
            $table->index('no_kk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
