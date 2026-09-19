<?php


namespace Modules\Library\app\Listeners\BookListeners\BookCreatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookCreated;
use Modules\Library\app\Events\Broadcasts\BookBroadcast;

class BookCreatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BookCreated $event)
    {
        broadcast(new BookBroadcast($event->book))->toOthers();
    }
}
