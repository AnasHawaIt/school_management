<?php

namespace Modules\Core\app\Listeners\Role;

use Modules\Core\app\Events\Role\RoleForceDeleted;

class LogRoleForceDeleted
{
    public function handle(RoleForceDeleted $event): void
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
            ->log('role.force_deleted');
    }
}
