<?php

namespace Modules\Core\app\Listeners\User\UserUpdated;

use Modules\Core\app\Events\Broadcasted\UserUpdatedBroadcasted;
use Modules\Core\app\Events\User\UserUpdated;

class BroadcastUserUpdated
{
    public function handle(UserUpdated $event): void
    {
        event(new UserUpdatedBroadcasted(
            user: $event->user,
            userId: $event->userId,
        ));
    }
}
