<?php

namespace Modules\Core\Listeners\Permission;


use Modules\Core\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\Events\Permission\PermissionDeleted;

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
