<?php


namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;


use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookDeletedBroadcastEventListener
{

    public function handle(BookDeleted $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
