<?php

namespace Modules\Library\app\Listeners\BookListeners\BookDeletedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookDeleted;
use Modules\Library\app\Events\Broadcasts\BookBroadcast;

class BookDeletedBroadcastEventListener implements ShouldQueue
{
    public function handle(BookDeleted $event): void
    {
        broadcast(
            new BookBroadcast(
                book: $event->book,
                action: 'deleted'
            )
        )->toOthers();
    }
}
