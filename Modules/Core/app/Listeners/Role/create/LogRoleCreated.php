<?php


namespace Modules\Core\Listeners\Role\create;

use Modules\Core\Events\Role\RoleCreated;

class LogRoleCreated
{
    public function handle(RoleCreated $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'role_name' => $role->name,
            ])
            ->log('role.created');
    }
}
