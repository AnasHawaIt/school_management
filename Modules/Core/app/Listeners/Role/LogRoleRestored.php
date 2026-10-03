<?php

namespace Modules\Core\Listeners\Role;

use Modules\Core\Events\Role\RoleRestored;

class LogRoleRestored
{
    public function handle(RoleRestored $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy(request()->user())
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'role_name' => $role->name,
            ])
            ->log('role.restored');
    }
}
