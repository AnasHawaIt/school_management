<?php

namespace Modules\Core\app\Listeners\Role\RolePermissionAttached;

use Modules\Core\app\Events\Role\RolePermissionAttached;
use Modules\Core\app\Events\Role\RolePermissionDetached;
use Modules\Core\app\Jobs\Role\SendRoleNotificationJob;

class NotifyRolePermissionChanged
{
    public function handle(
        RolePermissionAttached|RolePermissionDetached $event
    ): void {
        $isAttached = $event instanceof RolePermissionAttached;

        $action = $isAttached
            ? 'attached'
            : 'detached';

        $title = $isAttached
            ? 'Role permission added'
            : 'Role permission removed';

        $body = $isAttached
            ? "Permissions have been added to role \"{$event->role->name}\"."
            : "Permissions have been removed from role \"{$event->role->name}\".";

        SendRoleNotificationJob::dispatch(
            roleId: $event->role->id,
            eventType: "role.permission.{$action}",
            title: $title,
            body: $body,
            userId: $event->userId,
            permissionIds: $event->permissionIds,
        );
    }
}
