<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public array $changes
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('setting'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'settings.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'changes' => $this->changes,
        ];
    }
}
