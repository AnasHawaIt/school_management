<?php

namespace Modules\Core\app\Listeners\Role\RolePermissionAttached;

use Modules\Core\app\Events\Role\RolePermissionAttached;

class LogRolePermissionAttached
{
    public function handle(RolePermissionAttached $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy($event->userId)
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'permission_ids' => $event->permissionIds,
            ])
            ->log('role.permission_attached');
    }
}
