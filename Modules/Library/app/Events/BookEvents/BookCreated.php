<?php

namespace Modules\Library\app\Events\BookEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Book;

class BookCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Book $book,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}
