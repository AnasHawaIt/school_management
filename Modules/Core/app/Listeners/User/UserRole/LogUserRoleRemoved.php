<?php

namespace Modules\Core\app\Listeners\User\UserRole;

use Modules\Core\app\Events\User\UserRoleRemoved;

class LogUserRoleRemoved
{
    public function handle(UserRoleRemoved $event): void
    {
        $user = $event->user;
        $role = $event->role;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'role_name' => $role->name,
            ])
            ->log('user.role_removed');
    }
}
