<?php

namespace App\Jobs;

use App\Models\TelegramBroadcastQueue;
use App\Models\Penduduk;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTelegramBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public TelegramBroadcastQueue $broadcast
    ) {}

    public function handle(TelegramService $telegram): void
    {
        try {
            $this->broadcast->update(['status' => 'Processing']);

            $chatIds = $this->getTargetChatIds($this->broadcast->kategori_target);

            if (empty($chatIds)) {
                $this->broadcast->update([
                    'status' => 'Failed',
                    'waktu_selesai' => now(),
                ]);
                return;
            }

            $results = $telegram->broadcast($chatIds, $this->broadcast->pesan);

            $this->broadcast->update([
                'status' => 'Completed',
                'waktu_selesai' => now(),
            ]);

            Log::info("Broadcast completed: {$results['success']} success, {$results['failed']} failed");

        } catch (\Exception $e) {
            Log::error("Broadcast failed: " . $e->getMessage());
            
            $this->broadcast->update([
                'status' => 'Failed',
                'waktu_selesai' => now(),
            ]);

            throw $e;
        }
    }

    protected function getTargetChatIds(string $kategori): array
    {
        $query = Penduduk::whereNotNull('telegram_chat_id');

        switch ($kategori) {
            case 'semua':
                break;
            
            case 'aktif':
                $query->where('status_mutasi', 'Tetap');
                break;
            
            case 'laki-laki':
                $query->where('jenis_kelamin', 'L');
                break;
            
            case 'perempuan':
                $query->where('jenis_kelamin', 'P');
                break;
            
            default:
                if (str_starts_with($kategori, 'dusun:')) {
                    $dusun = str_replace('dusun:', '', $kategori);
                    $query->whereHas('keluarga', function ($q) use ($dusun) {
                        $q->where('dusun', $dusun);
                    });
                }
        }

        return $query->pluck('telegram_chat_id')->toArray();
    }
}
