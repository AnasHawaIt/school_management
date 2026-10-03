<?php

namespace Modules\Core\Listeners\Role\create;

use Modules\Core\Events\Role\RoleCreated;
use Modules\Core\Jobs\Role\BroadcastRoleChangedJob;

class BroadcastRoleCreated
{
    public function handle(RoleCreated $event): void
    {
        BroadcastRoleChangedJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.created',
            userId: $event->userId,
        );
    }
}
