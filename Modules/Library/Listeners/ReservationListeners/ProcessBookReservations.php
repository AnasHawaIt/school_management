<?php

namespace Modules\Library\Listeners\ReservationListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BorrowingEvents\BookAvailable;
use Modules\Library\Services\ReservationService;

class ProcessBookReservations implements ShouldQueue
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function handle(BookAvailable $event): void
    {
        $book = $event->book;

        $this->reservationService->processNextReservation(
            $book->id
        );
    }
}
