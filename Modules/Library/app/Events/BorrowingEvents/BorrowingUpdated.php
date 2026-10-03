<?php

namespace Modules\Library\Events\BorrowingEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;


class   BorrowingUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(
        public Borrowing $borrowing,
        public ?int      $userId = null,
        public ?string   $socketId = null
    ) {}
}

