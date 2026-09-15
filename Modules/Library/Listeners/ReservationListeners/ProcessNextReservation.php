<?php

namespace Modules\Library\Listeners\ReservationListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\ReservationEvents\ReservationCreated;
use Modules\Library\Services\ReservationService;

class ProcessNextReservation implements ShouldQueue
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function handle(ReservationCreated $event): void
    {
        $reservation = $event->reservation;

        $this->reservationService->processNextReservation(
            $reservation->book_id
        );
    }
}
