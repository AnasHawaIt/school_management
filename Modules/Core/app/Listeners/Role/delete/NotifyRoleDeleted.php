<?php

namespace Modules\Core\Listeners\Role\delete;

use Modules\Core\Events\Role\RoleDeleted;
use Modules\Core\Jobs\Role\SendRoleNotificationJob;

class NotifyRoleDeleted
{
    public function handle(RoleDeleted $event): void
    {
        SendRoleNotificationJob::dispatch(
            roleId: $event->role->id,
            eventType: 'role.deleted',
            title: 'Role deleted',
            body: "The role \"{$event->role->name}\" has been deleted.",
            userId: $event->userId,
        );
    }
}
