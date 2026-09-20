<?php

namespace Modules\Core\app\Listeners\Permission;

use Modules\Core\app\Events\Permission\PermissionUpdated;

class LogPermissionUpdated
{
    public function handle(PermissionUpdated $event): void
    {
        $permission = $event->permission;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'permission_id' => $permission->id,
                'old_values' => $event->oldValues,
                'new_values' => $event->newValues,
            ])
            ->log('permission.updated');
    }
}
