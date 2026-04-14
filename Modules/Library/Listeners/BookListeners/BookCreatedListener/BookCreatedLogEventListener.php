<?php

namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\BookEvents\BookCreated;

class BookCreatedLogEventListener
{
    public function handle(BookCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'subscription_created',
            'data' =>$event->book,
        ]);
    }
}
