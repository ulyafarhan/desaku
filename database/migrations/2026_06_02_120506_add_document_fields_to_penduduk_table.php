<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('telegram_chat_id');
            $table->string('foto_ktp')->nullable()->after('foto_profil');
            $table->string('foto_kk')->nullable()->after('foto_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropColumn(['foto_profil', 'foto_ktp', 'foto_kk']);
        });
    }
};
