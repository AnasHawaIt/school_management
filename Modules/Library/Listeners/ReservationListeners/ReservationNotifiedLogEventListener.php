<?php

namespace Modules\Library\Listeners\ReservationListeners;

use Modules\Library\Events\ReservationEvents\ReservationNotified;

class ReservationNotifiedLogEventListener
{
    public function handle(ReservationNotified $event): void
    {
        $reservation = $event->reservation;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($reservation)
            ->withProperties([
                'reservation_id' => $reservation->id,
            ])
            ->log('reservation.notified');
    }
}
