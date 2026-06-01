<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_surat_id');
            $table->string('status_sebelumnya', 20)->nullable();
            $table->string('status_baru', 20);
            $table->text('keterangan_update')->nullable();
            $table->unsignedBigInteger('diupdate_oleh')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('pengajuan_surat_id')
                ->references('id')
                ->on('pengajuan_surat')
                ->cascadeOnDelete();
            
            $table->foreign('diupdate_oleh')
                ->references('id')
                ->on('administrators')
                ->nullOnDelete();
            
            $table->index('pengajuan_surat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_pengajuan_surat');
    }
};
