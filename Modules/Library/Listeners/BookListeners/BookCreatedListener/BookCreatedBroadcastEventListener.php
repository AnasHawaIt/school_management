<?php


namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;


use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookCreatedBroadcastEventListener
{

    public function handle(BookCreated $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
