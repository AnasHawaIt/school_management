<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyDeleted;
use Modules\Library\app\Events\Broadcasts\BookCopyBroadcast;

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
