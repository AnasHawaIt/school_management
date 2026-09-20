<?php

namespace Modules\Core\app\Listeners\Role\create;

use Modules\Core\app\Events\Role\RoleCreated;
use Modules\Core\app\Jobs\Role\BroadcastRoleChangedJob;

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
