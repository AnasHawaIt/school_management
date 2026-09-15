<?php

namespace Modules\Library\app\Enums;

enum BorrowingStatus: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';

    case REJECTED = 'rejected';

    case BORROWED = 'borrowed';

    case LATE = 'late';

    case RETURNED = 'returned';

    case LOST = 'lost';

    case CANCELLED = 'cancelled';
}
