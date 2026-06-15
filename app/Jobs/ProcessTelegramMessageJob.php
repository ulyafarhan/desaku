<?php

namespace App\Jobs;

use App\Services\Contracts\AiProviderInterface;
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

    public function handle(
        AiProviderInterface $ai, 
        TelegramService $telegram,
        \App\Services\TelegramKnowledgeService $knowledge
    ): void {
        $context = $knowledge->retrieveContext($this->text);

        $aiResponse = $ai->generateResponse($this->text, $this->chatId, $context);

        if ($aiResponse) {
            $cacheKey = 'telegram_reply_' . md5(trim(strtolower($this->text)));
            \Illuminate\Support\Facades\Cache::put($cacheKey, $aiResponse, now()->addHours(2));

            $telegram->sendMessage($this->chatId, $aiResponse);

            return;
        }

        $telegram->sendMessage(
            $this->chatId,
            'Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti atau hubungi kantor desa.'
        );
    }
}
