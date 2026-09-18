<?php

namespace Modules\Core\app\Listeners\User\UserRole;

use Modules\Core\app\Events\User\UserRoleRemoved;
use Modules\Notifications\Services\NotificationService;

class NotifyRoleRemoved
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(UserRoleRemoved $event): void
    {
        $user = $event->user;
        $role = $event->role;

        $this->notificationService->send(
            user: $user,
            title: 'Role Removed',
            body: "The role '{$role->name}' has been removed from your account.",
            type: 'user.role_removed',
            data: [
                'user_id' => $user->id,
                'role_id' => $role->id,
                'role_name' => $role->name,
                'event' => 'user.role_removed',
            ],
        );
    }
}
