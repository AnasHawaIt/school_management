<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\CategoryEvents\CategoryUpdated;

class CategoryUpdateLogEventListener
{
    public function handle(CategoryUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryUpdated',
            'data' =>$event->category,
        ]);
    }
}
