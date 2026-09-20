<?php

namespace Modules\Library\app\Events\BorrowingEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Borrowing;

class BorrowingOverdue
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Borrowing $borrowing,
    )
    {
    }
}
