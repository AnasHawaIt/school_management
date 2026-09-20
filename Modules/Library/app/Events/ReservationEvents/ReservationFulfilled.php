<?php

namespace Modules\Library\app\Events\ReservationEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Reservation;

class ReservationFulfilled
{

    use Dispatchable, SerializesModels;

    public function __construct(
        public Reservation $reservation
    ) {
    }
}
