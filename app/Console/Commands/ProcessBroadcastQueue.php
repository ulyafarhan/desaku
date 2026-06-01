<?php

namespace App\Console\Commands;

use App\Models\TelegramBroadcastQueue;
use App\Jobs\ProcessTelegramBroadcastJob;
use Illuminate\Console\Command;

class ProcessBroadcastQueue extends Command
{
    protected $signature = 'telegram:process-broadcast';
    protected $description = 'Process pending Telegram broadcast queue';

    public function handle(): int
    {
        $this->info('Processing broadcast queue...');

        $broadcasts = TelegramBroadcastQueue::ready()->get();

        if ($broadcasts->isEmpty()) {
            $this->info('No broadcasts to process');
            return self::SUCCESS;
        }

        $this->info("Found {$broadcasts->count()} broadcasts");

        foreach ($broadcasts as $broadcast) {
            ProcessTelegramBroadcastJob::dispatch($broadcast);
            $this->info("Dispatched broadcast #{$broadcast->id}");
        }

        $this->info('✅ All broadcasts dispatched to queue');
        return self::SUCCESS;
    }
}
