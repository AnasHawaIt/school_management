<?php

namespace Modules\Library\app\Listeners\ReservationListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BorrowingEvents\BookAvailable;
use Modules\Library\app\Services\ReservationService;

class ProcessBookReservations implements ShouldQueue
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function handle(BookAvailable $event): void
    {
        $book = $event->book;

        $this->reservationService->notifyNextMember(
            $book->id
        );
    }
}
