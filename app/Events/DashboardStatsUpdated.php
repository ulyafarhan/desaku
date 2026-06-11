<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatsUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $stats,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('dashboard')];
    }
}
