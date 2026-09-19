<?php

namespace Modules\Core\app\Listeners\Permission;


use Modules\Core\app\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\app\Events\Permission\PermissionDeleted;

class BroadcastPermissionDeleted
{
    public function handle(PermissionDeleted $event): void
    {
        event(new PermissionBroadcast(
            permission: $event->permission,
            action: 'deleted'
        ));
    }
}
