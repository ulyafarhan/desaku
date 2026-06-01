<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_gampong', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 50)->unique();
            $table->text('nilai');
            $table->string('tipe_data', 20)->default('string');
            $table->string('deskripsi')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('kunci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gampong');
    }
};
