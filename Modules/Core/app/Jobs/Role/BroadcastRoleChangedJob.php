<?php

namespace Modules\Core\app\Jobs\Role;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

class BroadcastRoleChangedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public int $roleId,
        public string $eventType,
        public ?int $userId = null,
        public array $permissionIds = [],
        public array $oldPermissionIds = [],
        public array $oldValues = [],
        public array $newValues = [],
    ) {}

    public function handle(): void
    {
        $payload = [
            'role_id' => $this->roleId,
            'event' => $this->eventType,
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

        Broadcast::on(
            new PrivateChannel('admin.roles')
        )->as(
            'role.changed'
        )->with(
            $payload
        )->send();

        Log::info('Role change broadcasted', [
            'role_id' => $this->roleId,
            'event' => $this->eventType,
        ]);
    }
}
