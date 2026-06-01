<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_publik', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('konten');
            $table->string('kategori', 50);
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('author_id')
                ->references('id')
                ->on('administrators')
                ->nullOnDelete();
            
            $table->index('kategori');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_publik');
    }
};
