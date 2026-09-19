<?php


namespace Modules\Library\app\Listeners\BookListeners\BookUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookUpdated;
use Modules\Library\app\Events\Broadcasts\BookBroadcast;

class BookUpdatedBroadcastEventListener implements ShouldQueue
{

    public function handle(BookUpdated $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
