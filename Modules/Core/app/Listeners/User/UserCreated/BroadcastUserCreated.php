<?php

namespace Modules\Core\Listeners\User\UserCreated;

use Modules\Core\Events\Broadcasted\UserCreatedBroadcasted;
use Modules\Core\Events\User\UserCreated;

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
