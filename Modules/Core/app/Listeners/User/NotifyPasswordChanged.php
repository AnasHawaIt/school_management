<?php

namespace Modules\Core\app\Listeners\User;

use Modules\Core\app\Events\User\UserPasswordChanged;
use Modules\Notifications\Services\NotificationService;

class NotifyPasswordChanged
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(UserPasswordChanged $event): void
    {
        $user = $event->user;

        $this->notificationService->send(
            user: $user,
            title: 'Password Changed',
            body: 'Your account password has been changed successfully.',
            type: 'user.password_changed',
            data: [
                'user_id' => $user->id,
                'event' => 'user.password_changed',
            ],
        );
    }
}
