<?php

namespace Modules\Core\Listeners\Permission;

use Modules\Core\Events\Permission\PermissionDeleted;
use Modules\Core\Jobs\Permission\SendPermissionNotificationJob;

class NotifyPermissionDeleted
{
    public function handle(PermissionDeleted $event): void
    {
        SendPermissionNotificationJob::dispatch(
            title: 'Permission Deleted',
            body: "Permission '{$event->permission->name}' was deleted.",
            type: 'permission_deleted',
            data: [
                'permission_id' => $event->permission->id,
                'permission_name' => $event->permission->name,
            ],
        );
    }
}
