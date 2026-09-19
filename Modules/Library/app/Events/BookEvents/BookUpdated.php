<?php

namespace Modules\Library\app\Events\BookEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Book;


class BookUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Book $book,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

