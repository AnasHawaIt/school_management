<?php

namespace Modules\Core\Listeners\Permission;

use Modules\Core\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\Events\Permission\PermissionUpdated;

class BroadcastPermissionUpdated
{
    public function handle(PermissionUpdated $event): void
    {
        event(new PermissionBroadcast(
            permission: $event->permission,
            action: 'updated'
        ));
    }
}
