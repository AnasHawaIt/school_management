<?php

namespace Modules\Core\app\Listeners\Role\delete;

use Modules\Core\app\Events\Role\RoleDeleted;

class LogRoleDeleted
{
    public function handle(RoleDeleted $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy($event->userId)
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'role_name' => $role->name,
                'old_values' => $role->toArray(),
            ])
            ->log('role.deleted');
    }
}
