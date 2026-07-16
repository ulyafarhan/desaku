<?php

namespace App\Jobs;

use App\Models\InformasiPublik;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendNewsWhatsappNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $informasiId
    ) {}

    public function handle(WhatsAppService $whatsapp): void
    {
        $informasi = InformasiPublik::find($this->informasiId);
        if (!$informasi || !$informasi->is_published) {
            return;
        }

        $target = config('services.whatsapp.default_target');
        if (empty($target)) {
            Log::warning('WhatsApp default target not configured.');
            return;
        }

        $baseUrl = config('app.url');
        $articleUrl = rtrim($baseUrl, '/') . '/informasi/' . $informasi->slug;

        $rawContent = strip_tags($informasi->konten);
        $summary = Str::limit(trim(preg_replace('/\s+/', ' ', $rawContent)), 200, '...');

        $message = "*BERITA & PENGUMUMAN GAMPONG*\n\n";
        $message .= "*" . $informasi->judul . "*\n";
        $message .= "Kategori: #" . str_replace(' ', '', $informasi->kategori) . "\n\n";
        $message .= "\"" . $summary . "\"\n\n";
        $message .= "Baca selengkapnya: " . $articleUrl;

        $whatsapp->sendMessage($target, $message);
    }
}