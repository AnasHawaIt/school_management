<?php

namespace Modules\Library\app\Listeners\ReservationListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\ReservationEvents\ReservationCreated;
use Modules\Library\app\Services\ReservationService;

class ProcessNextReservation implements ShouldQueue
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function handle(ReservationCreated $event): void
    {
        $reservation = $event->reservation;

        $this->reservationService->notifyNextMember(
            $reservation->book_id
        );
    }
}
