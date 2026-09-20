<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Role;

class RoleBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Role $role,
        public string $action,
        public ?int $userId = null,
        public array $permissionIds = [],
        public array $oldPermissionIds = [],
        public array $oldValues = [],
        public array $newValues = [],
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.roles'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'role.changed';
    }

    public function broadcastWith(): array
    {
        $payload = [
            'role_id' => $this->role->id,
            'event' => $this->action,
            'user_id' => $this->userId,
        ];

        if (!empty($this->permissionIds)) {
            $payload['permission_ids'] = $this->permissionIds;
        }

        if (!empty($this->oldPermissionIds)) {
            $payload['old_permission_ids'] = $this->oldPermissionIds;
        }

        if (!empty($this->oldValues)) {
            $payload['old_values'] = $this->oldValues;
        }

        if (!empty($this->newValues)) {
            $payload['new_values'] = $this->newValues;
        }

        return $payload;
    }
}
