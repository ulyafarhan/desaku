<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kode_surat', 20)->unique();
            $table->string('nama_surat', 100);
            $table->string('template_view', 100);
            $table->json('schema_isian');
            $table->json('syarat_dokumen');
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_surat');
    }
};
