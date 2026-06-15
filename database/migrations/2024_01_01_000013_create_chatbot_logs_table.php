<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('telegram_chat_id', 50);
            $table->text('pesan_masuk');
            $table->text('balasan_ai');
            $table->integer('tokens_used')->default(0);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('telegram_chat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
