<?php

namespace Modules\Core\app\Listeners\Role\RolePermissionsSynced;

use Modules\Core\app\Events\Role\RolePermissionsSynced;
use Modules\Core\app\Jobs\Role\BroadcastRoleChangedJob;

class BroadcastRolePermissionsSynced
{
    public function handle(RolePermissionsSynced $event): void
    {
        BroadcastRoleChangedJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.permissions.synced',
            userId: $event->userId,
            permissionIds: $event->newPermissionIds,
            oldPermissionIds: $event->oldPermissionIds,
        );
    }
}
