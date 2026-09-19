<?php

namespace Modules\Core\app\Listeners\Role\RolePermissionAttached;

use Modules\Core\app\Events\Role\RolePermissionAttached;
use Modules\Core\app\Events\Role\RolePermissionDetached;

use Modules\Core\app\Jobs\Role\BroadcastRoleChangedJob;

class BroadcastRolePermissionChanged
{
    public function handle(
        RolePermissionAttached|RolePermissionDetached $event
    ): void {
        $eventType = $event instanceof RolePermissionAttached
            ? 'role.permission.attached'
            : 'role.permission.detached';

        BroadcastRoleChangedJob::dispatch(
            roleId: $event->role->id,
            eventType: $eventType,
            userId: $event->userId,
            permissionIds: $event->permissionIds,
        );
    }
}
