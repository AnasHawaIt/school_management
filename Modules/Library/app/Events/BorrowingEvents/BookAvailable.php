<?php


<<<<<<<< HEAD:Modules/Library/app/Events/BookEvents/BookAvailable.php
namespace Modules\Library\app\Events\BookEvents;
========
namespace Modules\Library\Events\BorrowingEvents;
>>>>>>>> 805201b1233d594b84aa4e236cb851d59081984d:Modules/Library/app/Events/BorrowingEvents/BookAvailable.php

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Book;

class BookAvailable
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Book $book,
    )
    {
    }
}
