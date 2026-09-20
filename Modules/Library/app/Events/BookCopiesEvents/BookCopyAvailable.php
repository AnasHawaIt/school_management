<?php

namespace Modules\Library\app\Events\BookCopiesEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\BookCopy;

class BookCopyAvailable
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public BookCopy $copy
    ) {}
}

