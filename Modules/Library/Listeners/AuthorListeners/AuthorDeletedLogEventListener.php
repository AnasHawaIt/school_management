<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\AuthorEvents\AuthorDeleted;

class AuthorDeletedLogEventListener
{
    public function handle(AuthorDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'AuthorDeleted',
            'data' =>$event->author,
        ]);
    }
}
