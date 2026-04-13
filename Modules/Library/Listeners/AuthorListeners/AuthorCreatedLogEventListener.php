<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\AuthorEvents\AuthorCreated;

class AuthorCreatedLogEventListener
{
    public function handle(AuthorCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'Author Created',
            'data' =>$event->author,
        ]);
    }
}
