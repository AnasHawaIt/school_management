<?php

namespace Modules\Core\app\Listeners\Role;

use Modules\Core\app\Events\Role\RoleRestored;

class LogRoleRestored
{
    public function handle(RoleRestored $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy($event->userId)
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'role_name' => $role->name,
            ])
            ->log('role.restored');
    }
}
