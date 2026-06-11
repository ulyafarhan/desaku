<?php

namespace App\Events;

use App\Models\InformasiPublik;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InformasiBaru
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InformasiPublik $informasi,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('informasi')];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->informasi->id,
            'judul' => $this->informasi->judul,
            'slug' => $this->informasi->slug,
            'kategori' => $this->informasi->kategori,
        ];
    }
}
