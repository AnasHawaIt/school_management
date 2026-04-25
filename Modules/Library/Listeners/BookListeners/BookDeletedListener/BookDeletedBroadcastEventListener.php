<?php


namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookDeletedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BookDeleted $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
