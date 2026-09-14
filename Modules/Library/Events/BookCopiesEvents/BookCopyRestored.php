<?php

namespace Modules\Library\Events\BookCopiesEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\BookCopy;

class BookCopyRestored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public BookCopy $copy,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}
