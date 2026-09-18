<?php

namespace Modules\Core\app\Listeners\User\UserRole;

use Modules\Core\app\Events\User\UserRoleAssigned;
use Modules\Notifications\Services\NotificationService;

class NotifyRoleAssigned
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(UserRoleAssigned $event): void
    {
        $user = $event->user;
        $role = $event->role;

        $this->notificationService->send(
            user: $user,
            title: 'Role Assigned',
            body: "The role '{$role->name}' has been assigned to your account.",
            type: 'user.role_assigned',
            data: [
                'user_id' => $user->id,
                'role_id' => $role->id,
                'role_name' => $role->name,
                'event' => 'user.role_assigned',
            ],
        );
    }
}
