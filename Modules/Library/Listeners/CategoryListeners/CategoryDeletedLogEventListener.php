<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;

class CategoryDeletedLogEventListener
{
    public function handle(CategoryDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryDeleted',
            'data' =>$event->category,
        ]);
    }
}
