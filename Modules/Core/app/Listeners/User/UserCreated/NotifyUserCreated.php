<?php

namespace Modules\Core\app\Listeners\User\UserCreated;

use Modules\Core\app\Events\User\UserCreated;
use Modules\Notifications\Services\NotificationService;

class NotifyUserCreated
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        $this->notificationService->send(
            user: $user,
            title: 'Account Created',
            body: 'Your school management account has been created successfully.',
            type: 'user.created',
            data: [
                'user_id' => $user->id,
                'event' => 'user.created',
            ],
        );
    }
}
