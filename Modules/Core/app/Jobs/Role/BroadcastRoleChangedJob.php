<?php

namespace Modules\Core\app\Jobs\Role;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Events\Broadcasted\RoleBroadcast;

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
        $role = Role::find($this->roleId);

        if (!$role) {
            return;
        }

        event(new RoleBroadcast(
            role: $role,
            action: $this->eventType,
            userId: $this->userId,
            permissionIds: $this->permissionIds,
            oldPermissionIds: $this->oldPermissionIds,
            oldValues: $this->oldValues,
            newValues: $this->newValues,
        ));

        Log::info('Role change broadcast dispatched', [
            'role_id' => $this->roleId,
            'event' => $this->eventType,
        ]);
    }
}
