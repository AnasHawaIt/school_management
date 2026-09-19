<?php

namespace Modules\Core\app\Listeners\Role\delete;

use Modules\Core\app\Events\Role\RoleDeleted;
use Modules\Core\app\Jobs\Role\BroadcastRoleChangedJob;

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
