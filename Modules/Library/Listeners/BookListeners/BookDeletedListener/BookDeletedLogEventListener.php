<?php

namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;


use Modules\Library\Entities\EventLog;
use Modules\Library\Events\BookEvents\BookDeleted;

class BookDeletedLogEventListener
{
    public function handle(BookDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'TransactionDeleted',
            'data' =>$event->book,
        ]);
    }
}
