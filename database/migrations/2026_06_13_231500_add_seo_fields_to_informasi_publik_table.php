<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informasi_publik', function (Blueprint $table) {
            $table->text('meta_description')->nullable()->after('cover_image');
            $table->string('kata_kunci')->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('informasi_publik', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'kata_kunci']);
        });
    }
};
