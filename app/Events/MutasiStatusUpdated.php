<?php

namespace App\Events;

use App\Models\MutasiPenduduk;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MutasiStatusUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public MutasiPenduduk $mutasi,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('mutasi'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->mutasi->id,
            'nik' => $this->mutasi->nik,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'jenis_mutasi' => $this->mutasi->jenis_mutasi,
        ];
    }
}
