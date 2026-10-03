<?php

namespace Modules\Library\Enums;

enum BookCopiesStatus: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case BORROWED = 'borrowed';
    case LOST = 'lost';
    case DAMAGED = 'damaged';
    case MAINTENANCE = 'maintenance';
}
