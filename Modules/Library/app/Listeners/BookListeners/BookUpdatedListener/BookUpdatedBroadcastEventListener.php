<?php

namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\Broadcasts\BookBroadcast;

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
