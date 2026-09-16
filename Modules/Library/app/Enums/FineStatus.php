<?php

namespace Modules\Library\app\Enums;

enum FineStatus: string
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case WAIVED = 'waived';
}
