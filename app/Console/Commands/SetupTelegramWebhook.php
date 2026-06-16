<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

/**
 * Perintah Artisan untuk mendaftarkan dan mengonfigurasi URL Webhook Bot Telegram ke server Telegram API.
 *
 * @package App\Console\Commands
 */
class SetupTelegramWebhook extends Command
{
    /**
     * Nama dan tanda tangan perintah Artisan.
     *
     * @var string
     */
    protected $signature = 'telegram:setup-webhook';

    /**
     * Deskripsi singkat perintah Artisan.
     *
     * @var string
     */
    protected $description = 'Setup Telegram webhook untuk bot';

    /**
     * Eksekusi utama perintah untuk mendaftarkan URL Webhook Telegram.
     */
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
