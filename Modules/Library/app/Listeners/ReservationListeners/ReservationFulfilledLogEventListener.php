<?php

namespace Modules\Library\Listeners\ReservationListeners;


use Modules\Library\Events\ReservationEvents\ReservationFulfilled;

class ReservationFulfilledLogEventListener
{
    public function handle(ReservationFulfilled $event): void
    {
        $reservation = $event->reservation;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($reservation)
            ->withProperties([
                'reservation_id' => $reservation->id,
            ])
            ->log('reservation.fulfilled');
    }
}
