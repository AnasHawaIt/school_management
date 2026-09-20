<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Jobs\ProcessReservationQueueJob;
use Modules\Library\app\Events\BorrowingEvents\BookAvailable;

class ProcessReservationQueueListener
{
    public function handle(BookAvailable $event): void
    {
        ProcessReservationQueueJob::dispatch(
            $event->book->id
        );
    }
}
