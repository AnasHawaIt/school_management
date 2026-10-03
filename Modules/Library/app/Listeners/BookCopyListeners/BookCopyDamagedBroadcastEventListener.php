<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyDamaged;
use Modules\Library\Events\Broadcasts\BookCopyBroadcast;

class BookCopyDamagedBroadcastEventListener implements ShouldQueue
{
    public function handle(BookCopyDamaged $event): void
    {
        broadcast(
            new BookCopyBroadcast(
                $event->copy
            )
        )->toOthers();
    }
}
