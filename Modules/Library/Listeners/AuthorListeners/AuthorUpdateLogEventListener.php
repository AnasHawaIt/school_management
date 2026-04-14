<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\AuthorEvents\AuthorUpdated;

class AuthorUpdateLogEventListener
{
    public function handle(AuthorUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'AuthorUpdated',
            'data' =>$event->changes
        ]);
    }
}
