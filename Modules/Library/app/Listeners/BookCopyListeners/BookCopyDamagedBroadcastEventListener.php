<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyDamaged;
use Modules\Library\app\Events\Broadcasts\BookCopyBroadcast;

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
