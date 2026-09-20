<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyUpdated;
use Modules\Library\Events\Broadcasts\BookCopyBroadcast;

class BookCopyUpdatedBroadcastEventListener implements ShouldQueue
{
    public function handle(BookCopyUpdated $event): void
    {
        broadcast(
            new BookCopyBroadcast(
                $event->copy
            )
        )->toOthers();
    }
}
