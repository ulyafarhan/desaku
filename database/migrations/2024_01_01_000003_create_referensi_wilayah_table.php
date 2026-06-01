<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referensi_wilayah', function (Blueprint $table) {
            $table->string('kode_wilayah', 15)->primary();
            $table->string('nama_wilayah', 100);
            $table->enum('level', ['provinsi', 'kabupaten', 'kecamatan', 'desa']);
            $table->string('parent_kode', 15)->nullable();
            
            $table->foreign('parent_kode')
                ->references('kode_wilayah')
                ->on('referensi_wilayah')
                ->restrictOnDelete();
            
            $table->index('parent_kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referensi_wilayah');
    }
};
