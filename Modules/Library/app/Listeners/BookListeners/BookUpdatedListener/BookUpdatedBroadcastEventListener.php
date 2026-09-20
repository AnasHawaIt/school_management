<?php

namespace Modules\Library\app\Listeners\BookListeners\BookUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookUpdated;
use Modules\Library\app\Events\Broadcasts\BookBroadcast;

class BookUpdatedBroadcastEventListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(BookUpdated $event): void
    {
        broadcast(
            new BookBroadcast(
                book: $event->book,
                action: 'updated',
                changes: $event->changes
            )
        );
    }
}
