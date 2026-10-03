<?php

namespace Modules\Core\Listeners\Permission;

use Modules\Core\Events\Permission\PermissionRestored;

class LogPermissionRestored
{
    public function handle(PermissionRestored $event): void
    {
        $permission = $event->permission;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
            ])
            ->log('permission.restored');
    }
}
