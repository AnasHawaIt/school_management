<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\PublishersEvents\PublishersUpdated;

class PublishersUpdateLogEventListener
{
    public function handle(PublishersUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'Publishers Updated',
            'data' =>$event->publisher,
        ]);
    }
}
