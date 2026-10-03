<?php

namespace Modules\Library\Events\BookEvents;

use Modules\Library\Entities\Book;

class BookDeleted
{
    public function __construct(
        public Book $book,
        public ?int $userId = null
    ) {}
}


