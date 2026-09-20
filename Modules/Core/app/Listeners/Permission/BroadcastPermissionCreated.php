<?php

namespace Modules\Core\app\Listeners\Permission;


use Modules\Core\app\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\app\Events\Permission\PermissionCreated;

class BroadcastPermissionCreated
{
    public function handle(PermissionCreated $event): void
    {
        event(new PermissionBroadcast(
            permission: $event->permission,
            action: 'created'
        ));
    }
}
