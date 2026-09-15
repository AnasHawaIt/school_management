<?php

namespace Modules\Library\app\Enums;

enum ReservationStatus: string
{
        case PENDING = 'pending';

        case NOTIFIED = 'notified';

        case FULFILLED = 'fulfilled';

        case CANCELLED = 'cancelled';

        case EXPIRED = 'expired';
}

