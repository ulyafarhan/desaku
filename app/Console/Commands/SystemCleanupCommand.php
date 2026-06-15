<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SystemCleanupCommand extends Command
{
    protected $signature = 'system:cleanup';
    protected $description = 'Perform periodic system maintenance and data cleanup';

    public function handle(): int
    {
        $this->info('Starting system cleanup...');
        Log::info('System cleanup started.');

        try {
            $deletedChatbotLogs = DB::table('chatbot_logs')
                ->where('created_at', '<', now()->subDays(30))
                ->delete();
            $this->info("Deleted $deletedChatbotLogs chatbot logs older than 30 days.");
            Log::info("System cleanup: Deleted $deletedChatbotLogs chatbot logs.");

            $deletedAuditLogs = DB::table('audit_logs')
                ->where('created_at', '<', now()->subDays(90))
                ->delete();
            $this->info("Deleted $deletedAuditLogs audit logs older than 90 days.");
            Log::info("System cleanup: Deleted $deletedAuditLogs audit logs.");

            $this->info('Pruning expired personal access tokens...');
            Artisan::call('sanctum:prune-expired');
            $this->info('Sanctum tokens pruned.');

            $this->info('Pruning failed queue jobs...');
            Artisan::call('queue:prune-failed', ['--hours' => 72]);
            $this->info('Failed queue jobs pruned.');

            $this->info('System cleanup completed successfully.');
            Log::info('System cleanup completed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during system cleanup: ' . $e->getMessage());
            Log::error('System cleanup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
