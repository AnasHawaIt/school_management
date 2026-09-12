<?php

namespace Modules\Library\Events\ReservationEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Reservation;

class ReservationCreated
{

    use Dispatchable, SerializesModels;

    public function __construct(
        public Reservation $reservation
    ) {
    }
}
