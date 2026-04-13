<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\CategoryEvents\CategoryCreated;

class CategoryCreatedLogEventListener
{
    public function handle(CategoryCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryCreated',
            'data' =>$event->category
        ]);
    }
}
