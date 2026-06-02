<?php

namespace App\Jobs;

use App\Services\GeminiAiService;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public string $chatId,
        public string $text
    ) {}

    public function handle(GeminiAiService $gemini, TelegramService $telegram): void
    {
        $aiResponse = $gemini->generateResponse($this->text, $this->chatId);

        if ($aiResponse) {
            $telegram->sendMessage($this->chatId, $aiResponse);

            return;
        }

        $telegram->sendMessage(
            $this->chatId,
            'Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti atau hubungi kantor desa.'
        );
    }
}
