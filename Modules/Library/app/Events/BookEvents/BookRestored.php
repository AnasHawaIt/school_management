<?php


namespace Modules\Library\app\Events\BookEvents;

use Modules\Library\app\Entities\Book;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Book $book,
        public ?int $userId = null
    ) {}
}

