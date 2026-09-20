<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyUpdated;
use Modules\Library\app\Events\Broadcasts\BookCopyBroadcast;

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
