<?php

namespace Modules\Core\app\Listeners\Permission;

use Modules\Core\app\Events\Permission\PermissionForceDeleted;

class LogPermissionForceDeleted
{
    public function handle(PermissionForceDeleted $event): void
    {
        $permission = $event->permission;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
                'old_values' => $permission->toArray(),
            ])
            ->log('permission.force_deleted');
    }
}
