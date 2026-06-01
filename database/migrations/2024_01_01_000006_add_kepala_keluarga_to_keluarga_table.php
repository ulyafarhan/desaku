<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->string('kepala_keluarga_nik', 16)->nullable();
            
            $table->foreign('kepala_keluarga_nik')
                ->references('nik')
                ->on('penduduk')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropForeign(['kepala_keluarga_nik']);
            $table->dropColumn('kepala_keluarga_nik');
        });
    }
};
