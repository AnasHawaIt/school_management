<?php

namespace Modules\Library\app\Listeners\ReservationListeners;

use Modules\Library\app\Events\ReservationEvents\ReservationCancelled;

class ReservationCancelledLogEventListener
{
    public function handle(ReservationCancelled $event): void
    {
        $reservation = $event->reservation;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($reservation)
            ->withProperties([
                'reservation_id' => $reservation->id,
            ])
            ->log('reservation.cancelled');
    }
}
