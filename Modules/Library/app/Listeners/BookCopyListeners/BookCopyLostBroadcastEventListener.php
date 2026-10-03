<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyLost;
use Modules\Library\Events\Broadcasts\BookCopyBroadcast;

class BookCopyLostBroadcastEventListener implements ShouldQueue
{
    public function handle(BookCopyLost $event): void
    {
        broadcast(
            new BookCopyBroadcast(
                $event->copy
            )
        )->toOthers();
    }
}
