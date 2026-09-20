<?php


namespace Modules\Core\app\Listeners\Permission;

use Modules\Core\app\Events\Permission\PermissionCreated;

class LogPermissionCreated
{
    public function handle(PermissionCreated $event): void
    {
        $permission = $event->permission;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
            ])
            ->log('permission.created');
    }
}
