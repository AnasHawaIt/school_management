<?php

namespace Modules\Core\app\Listeners\Role\delete;

use Modules\Core\app\Events\Role\RoleDeleted;
use Modules\Core\app\Jobs\Role\SendRoleNotificationJob;

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
