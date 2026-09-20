<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Setting;

class SettingBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Setting $setting,
        public string $action,
        public array $changes = [],
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('setting'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'setting.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->setting->id,
            'key' => $this->setting->key,
            'value' => $this->setting->value,
            'type' => $this->setting->type,
            'changes' => $this->changes,
        ];
    }
}
