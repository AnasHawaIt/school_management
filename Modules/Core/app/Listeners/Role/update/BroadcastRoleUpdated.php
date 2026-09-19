<?php

namespace Modules\Core\app\Listeners\Role\update;

use Modules\Core\app\Events\Role\RoleUpdated;
use Modules\Core\app\Jobs\Role\BroadcastRoleChangedJob;

class BroadcastRoleUpdated
{
    public function handle(RoleUpdated $event): void
    {
        BroadcastRoleChangedJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.updated',
            userId: $event->userId,
            oldValues: $event->oldValues,
            newValues: $event->newValues,
        );
    }
}
