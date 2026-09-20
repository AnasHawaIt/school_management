<?php

namespace Modules\Core\app\Listeners\User\UserCreated;

use Modules\Core\app\Events\Broadcasted\UserCreatedBroadcasted;
use Modules\Core\app\Events\User\UserCreated;

class BroadcastUserCreated
{
    public function handle(UserCreated $event): void
    {
        event(new UserCreatedBroadcasted(
            user: $event->user,
            userId: $event->userId,
        ));
    }
}
