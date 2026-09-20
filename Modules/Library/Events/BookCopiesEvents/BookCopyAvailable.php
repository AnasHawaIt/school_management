<?php

namespace Modules\Library\Events\BookCopiesEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\BookCopy;

class BookCopyAvailable
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public BookCopy $copy
    ) {}
}

