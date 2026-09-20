<?php

namespace Modules\Library\app\Events\BorrowingEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Borrowing;

class BorrowingForceDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Borrowing $borrowing,
        public ?int $userId = null,
        public ?string $reason = null,
    ) {}
}
