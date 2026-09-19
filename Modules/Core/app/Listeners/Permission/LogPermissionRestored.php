<?php

namespace Modules\Core\app\Listeners\Permission;

use Modules\Core\app\Events\Permission\PermissionRestored;

class LogPermissionRestored
{
    public function handle(PermissionRestored $event): void
    {
        $permission = $event->permission;

        activity()
            ->causedBy($event->userId)
            ->performedOn($permission)
            ->withProperties([
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
            ])
            ->log('permission.restored');
    }
}
