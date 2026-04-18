<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\Events\PublishersEvents\PublishersDeleted;

class PublishersDeletedLogEventListener
{
    public function handle(PublishersDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryDeleted',
            'data' =>$event->publisher,
        ]);
    }
}
