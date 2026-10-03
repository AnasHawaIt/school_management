<?php

namespace Modules\Core\Listeners\Permission;


use Modules\Core\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\Events\Permission\PermissionCreated;

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
