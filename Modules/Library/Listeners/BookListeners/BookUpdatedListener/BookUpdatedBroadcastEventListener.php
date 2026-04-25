<?php


namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookUpdatedBroadcastEventListener implements ShouldQueue
{

    public function handle(BookUpdated $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
