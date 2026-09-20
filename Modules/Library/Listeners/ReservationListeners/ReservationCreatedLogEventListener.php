<?php

namespace Modules\Library\Listeners\ReservationListeners;

use Modules\Library\Events\ReservationEvents\ReservationCreated;

class ReservationCreatedLogEventListener
{
    public function handle(ReservationCreated $event): void
    {
        $reservation = $event->reservation;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($reservation)
            ->withProperties([
                'reservation_id' => $reservation->id,
            ])
            ->log('reservation.created');
    }
}
