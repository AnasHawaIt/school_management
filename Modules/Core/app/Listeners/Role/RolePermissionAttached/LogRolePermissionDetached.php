<?php

namespace Modules\Core\Listeners\Role\RolePermissionAttached;

use Modules\Core\Events\Role\RolePermissionDetached;

class LogRolePermissionDetached
{
    public function handle(RolePermissionDetached $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'permission_ids' => $event->permissionIds,
            ])
            ->log('role.permission_detached');
    }
}
