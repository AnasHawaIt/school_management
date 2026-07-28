<?php

namespace Modules\Library\Events\BorrowingEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;

class BorrowingCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Borrowing $borrowing,
        public ?int $userId = null,
        public ?string $reason = null,
    ) {}
}
