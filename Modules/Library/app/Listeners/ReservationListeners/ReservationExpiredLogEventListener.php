<?php

namespace Modules\Library\app\Listeners\ReservationListeners;

use Modules\Library\app\Events\ReservationEvents\ReservationExpired;

class ReservationExpiredLogEventListener
{
    public function handle(ReservationExpired $event): void
    {
        $reservation = $event->reservation;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($reservation)
            ->withProperties([
                'reservation_id' => $reservation->id,
            ])
            ->log('reservation.expired');
    }
}
