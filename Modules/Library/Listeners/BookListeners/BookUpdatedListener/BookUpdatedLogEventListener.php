<?php

namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\BookEvents\BookUpdated;

class BookUpdatedLogEventListener
{
    public function handle(BookUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'TransactionUpdated',
            'data' =>$event->book,
        ]);
    }
}
