<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluarga', function (Blueprint $table) {
            $table->string('no_kk', 16)->primary();
            $table->text('alamat');
            $table->string('dusun', 50);
            $table->string('rt_rw', 10);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
