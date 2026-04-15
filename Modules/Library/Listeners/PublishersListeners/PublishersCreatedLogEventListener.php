<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Events\PublishersEvents\PublishersCreated;

class PublishersCreatedLogEventListener
{
    public function handle(PublishersCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryCreated',
            'data' =>$event->publishers
        ]);
    }
}
