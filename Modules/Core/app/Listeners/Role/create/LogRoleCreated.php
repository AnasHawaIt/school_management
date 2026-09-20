<?php


namespace Modules\Core\app\Listeners\Role\create;

use Modules\Core\app\Events\Role\RoleCreated;

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
