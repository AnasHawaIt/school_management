<?php

namespace Modules\Core\app\Listeners\Role\RolePermissionsSynced;

use Modules\Core\app\Events\Role\RolePermissionsSynced;
use Modules\Core\app\Jobs\Role\SendRoleNotificationJob;

class NotifyRolePermissionsSynced
{
    public function handle(RolePermissionsSynced $event): void
    {
        SendRoleNotificationJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.permissions.synced',
            title: 'Role permissions updated',
            body: "Permissions for role \"{$event->role->name}\" have been updated.",
            userId: $event->userId,
            oldPermissionIds: $event->oldPermissionIds,
            permissionIds: $event->newPermissionIds,
        );
    }
}
