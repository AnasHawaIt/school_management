<?php

namespace Modules\Library\app\Events\BorrowingEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Borrowing;


class   BorrowingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(
        public Borrowing $borrowing,
        public ?int      $userId = null,
        public ?string   $socketId = null
    ) {}
}

