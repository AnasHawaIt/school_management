<?php

namespace Modules\Core\Listeners\User\UserUpdated;

use Modules\Core\Events\Broadcasted\UserUpdatedBroadcasted;
use Modules\Core\Events\User\UserUpdated;

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
