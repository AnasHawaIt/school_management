<?php


namespace Modules\Core\app\Listeners\Role\update;

use Modules\Core\app\Events\Role\RoleUpdated;

class LogRoleUpdated
{
    public function handle(RoleUpdated $event): void
    {
        $role = $event->role;

        activity()
            ->causedBy($event->userId)
            ->performedOn($role)
            ->withProperties([
                'role_id' => $role->id,
                'old_values' => $event->oldValues,
                'new_values' => $event->newValues,
            ])
            ->log('role.updated');
    }
}
