<?php

namespace Modules\Core\app\Listeners\Role;

use Modules\Core\app\Events\Role\RolePermissionsSynced;

class LogRolePermissionsSynced
{
    public function handle(RolePermissionsSynced $event): void
    {
        $role = $event->role;

        $added = array_values(
            array_diff(
                $event->newPermissionIds,
                $event->oldPermissionIds
            )
        );

        $removed = array_values(
            array_diff(
                $event->oldPermissionIds,
                $event->newPermissionIds
            )
        );

        activity()
            ->causedBy($event->userId)
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'old_permission_ids' => $event->oldPermissionIds,
                'new_permission_ids' => $event->newPermissionIds,
                'added_permission_ids' => $added,
                'removed_permission_ids' => $removed,
            ])
            ->log('role.permissions_synced');
    }
}
