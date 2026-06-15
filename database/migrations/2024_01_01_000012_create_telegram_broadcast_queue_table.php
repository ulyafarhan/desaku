<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_broadcast_queue', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('pesan');
            $table->string('kategori_target', 50);
            $table->enum('status', ['Queued', 'Processing', 'Completed', 'Failed'])->default('Queued');
            $table->timestamp('jadwal_kirim')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->ulid('created_by')->nullable();
            
            $table->foreign('created_by')
                ->references('id')
                ->on('administrators')
                ->nullOnDelete();
            
            $table->index('status');
            $table->index('jadwal_kirim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_broadcast_queue');
    }
};
