<?php

namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\app\Jobs\ProcessReservationQueueJob;
use Modules\Library\Events\BorrowingEvents\BookAvailable;

class ProcessReservationQueueListener
{
    public function handle(BookAvailable $event): void
    {
        ProcessReservationQueueJob::dispatch(
            $event->book->id
        );
    }
}
