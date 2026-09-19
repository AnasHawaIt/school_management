<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Permission;

class PermissionBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Permission $permission,
        public string $action
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('permission'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'permission.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->permission->id,
            'name' => $this->permission->name,
            'module' => $this->permission->module,
            'description' => $this->permission->description,
        ];
    }
}
