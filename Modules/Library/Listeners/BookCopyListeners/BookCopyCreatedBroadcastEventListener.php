<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyCreated;
use Modules\Library\Events\Broadcasts\BookCopyBroadcast;

class BookCopyCreatedBroadcastEventListener implements ShouldQueue
{
    public function handle(BookCopyCreated $event): void
    {
        broadcast(
            new BookCopyBroadcast(
                $event->copy
            )
        )->toOthers();
    }
}
