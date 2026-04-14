<?php


namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;

use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookUpdatedBroadcastEventListener
{

    public function handle(BookUpdated $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
