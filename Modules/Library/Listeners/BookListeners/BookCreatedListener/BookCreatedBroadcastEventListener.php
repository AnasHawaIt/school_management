<?php


namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\Broadcasts\BookBroadcast;

class BookCreatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BookCreated $event)
    {
        broadcast(
            new BookBroadcast(
                book: $event->book,
                action: 'created'
            )
        )->toOthers();
    }
}
