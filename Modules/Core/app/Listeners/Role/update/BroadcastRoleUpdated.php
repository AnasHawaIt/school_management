<?php

namespace Modules\Core\Listeners\Role\update;

use Modules\Core\Events\Role\RoleUpdated;
use Modules\Core\Jobs\Role\BroadcastRoleChangedJob;

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
