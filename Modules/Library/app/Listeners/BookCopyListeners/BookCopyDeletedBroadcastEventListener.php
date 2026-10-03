<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyDeleted;
use Modules\Library\Events\Broadcasts\BookCopyBroadcast;

class BookCopyDeletedBroadcastEventListener implements ShouldQueue
{
    public function handle(BookCopyDeleted $event): void
    {
        broadcast(
            new BookCopyBroadcast(
                $event->copy
            )
        )->toOthers();
    }
}
