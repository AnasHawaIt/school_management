<?php

namespace Modules\Library\app\Events\BookEvents;

use Modules\Library\app\Entities\Book;

class BookDeleted
{
    public function __construct(
        public Book $book,
        public ?int $userId = null
    ) {}
}


