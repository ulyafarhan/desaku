<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetupTelegramWebhook extends Command
{
    protected $signature = 'telegram:setup-webhook';
    protected $description = 'Setup Telegram webhook untuk bot';

    public function handle(TelegramService $telegram): int
    {
        $this->info('Setting up Telegram webhook...');

        $webhookUrl = config('services.telegram.webhook_url');
        
        if (empty($webhookUrl)) {
            $this->error('TELEGRAM_WEBHOOK_URL tidak diset di .env');
            return self::FAILURE;
        }

        $this->info("Webhook URL: {$webhookUrl}");

        if ($telegram->setWebhook($webhookUrl)) {
            $this->info('✅ Webhook berhasil disetup!');
            
            $botInfo = $telegram->getMe();
            if ($botInfo) {
                $this->info("Bot Name: {$botInfo['first_name']}");
                $this->info("Bot Username: @{$botInfo['username']}");
            }
            
            return self::SUCCESS;
        }

        $this->error('❌ Gagal setup webhook');
        return self::FAILURE;
    }
}
