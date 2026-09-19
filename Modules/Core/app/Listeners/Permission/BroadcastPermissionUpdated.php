<?php

namespace Modules\Core\app\Listeners\Permission;

use Modules\Core\app\Events\Broadcasted\PermissionBroadcast;
use Modules\Core\app\Events\Permission\PermissionUpdated;

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
