<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyCreated;
use Modules\Library\app\Events\Broadcasts\BookCopyBroadcast;

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
