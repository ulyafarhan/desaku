<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider utama aplikasi untuk registrasi binding dan inisialisasi boot.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan binding service container.
     *
     * Menginisialisasi AiProviderInterface sesuai dengan konfigurasi aktif (Gemini/OpenAI).
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Contracts\AiProviderInterface::class, \App\Services\FallbackAiService::class);
    }

    /**
     * Menjalakan inisialisasi boot aplikasi.
     *
     * Mendaftarkan event listeners untuk membersihkan cache statistik,
     * serta mengisi konfigurasi AI dan penyimpanan dari database pengaturan gampong.
     */
    public function boot(): void
    {
        $clearStatistikCache = function () {
            \Illuminate\Support\Facades\Cache::forget('statistik_demografi');
            \Illuminate\Support\Facades\Cache::forget('statistik_layanan');
        };

        \App\Models\Penduduk::saved($clearStatistikCache);
        \App\Models\Penduduk::deleted($clearStatistikCache);

        \App\Models\Keluarga::saved($clearStatistikCache);
        \App\Models\Keluarga::deleted($clearStatistikCache);

        \App\Models\PengajuanSurat::saved($clearStatistikCache);
        \App\Models\PengajuanSurat::deleted($clearStatistikCache);

        \App\Models\MutasiPenduduk::saved($clearStatistikCache);
        \App\Models\MutasiPenduduk::deleted($clearStatistikCache);

        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan_gampong')) {
            if ($aiActiveProvider = \App\Models\PengaturanGampong::get('ai_active_provider')) {
                config(['services.ai.active_provider' => $aiActiveProvider]);
            }
            if ($aiGeminiKey = \App\Models\PengaturanGampong::get('ai_gemini_key')) {
                config(['services.gemini.api_key' => $aiGeminiKey]);
            }
            if ($aiOpenAiKey = \App\Models\PengaturanGampong::get('ai_openai_key')) {
                config(['services.ai.openai.api_key' => $aiOpenAiKey]);
            }
            if ($aiOpenAiModel = \App\Models\PengaturanGampong::get('ai_openai_model')) {
                config(['services.ai.openai.model' => $aiOpenAiModel]);
            }
            if ($aiOpenAiBaseUrl = \App\Models\PengaturanGampong::get('ai_openai_base_url')) {
                config(['services.ai.openai.base_url' => $aiOpenAiBaseUrl]);
            }

            if ($storageActiveDisk = \App\Models\PengaturanGampong::get('storage_active_disk')) {
                config(['filesystems.default' => $storageActiveDisk]);
            }
            if ($s3Key = \App\Models\PengaturanGampong::get('storage_s3_key')) {
                config(['filesystems.disks.s3.key' => $s3Key]);
            }
            if ($s3Secret = \App\Models\PengaturanGampong::get('storage_s3_secret')) {
                config(['filesystems.disks.s3.secret' => $s3Secret]);
            }
            if ($s3Bucket = \App\Models\PengaturanGampong::get('storage_s3_bucket')) {
                config(['filesystems.disks.s3.bucket' => $s3Bucket]);
            }
            if ($s3Region = \App\Models\PengaturanGampong::get('storage_s3_region')) {
                config(['filesystems.disks.s3.region' => $s3Region]);
            }
            if ($s3Endpoint = \App\Models\PengaturanGampong::get('storage_s3_endpoint')) {
                config(['filesystems.disks.s3.endpoint' => $s3Endpoint]);
            }
            if ($s3Url = \App\Models\PengaturanGampong::get('storage_s3_url')) {
                config(['filesystems.disks.s3.url' => $s3Url]);
            }
            if ($s3UsePathStyle = \App\Models\PengaturanGampong::get('storage_s3_use_path_style_endpoint')) {
                config(['filesystems.disks.s3.use_path_style_endpoint' => filter_var($s3UsePathStyle, FILTER_VALIDATE_BOOLEAN)]);
            }
        }
    }
}
