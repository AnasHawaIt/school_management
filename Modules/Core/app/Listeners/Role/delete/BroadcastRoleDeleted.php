<?php

namespace Modules\Core\Listeners\Role\delete;

use Modules\Core\Events\Role\RoleDeleted;
use Modules\Core\Jobs\Role\BroadcastRoleChangedJob;

class BroadcastRoleDeleted
{
    public function handle(RoleDeleted $event): void
    {
        BroadcastRoleChangedJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.deleted',
            userId: $event->userId,
        );
    }
}
