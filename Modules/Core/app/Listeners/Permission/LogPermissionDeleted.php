<?php

namespace Modules\Core\Listeners\Permission;

use Modules\Core\Events\Permission\PermissionDeleted;

class LogPermissionDeleted
{
    public function handle(PermissionDeleted $event): void
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
            ->log('permission.deleted');
    }
}
