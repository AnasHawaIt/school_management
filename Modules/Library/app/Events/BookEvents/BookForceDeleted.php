<?php

namespace Modules\Library\app\Events\BookEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Book;

class BookForceDeleted
{

    use Dispatchable, SerializesModels;

    public function __construct(
        public Book $book,
        public ?int $userId = null
    ) {}
}


