<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardProgressUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $data
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard-progress'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'dashboard.progress.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
