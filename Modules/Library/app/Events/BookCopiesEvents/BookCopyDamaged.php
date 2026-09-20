<?php

namespace Modules\Library\app\Events\BookCopiesEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\BookCopy;

class BookCopyDamaged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public BookCopy $copy,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}
