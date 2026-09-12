<?php

namespace Modules\Library\Events\AuthorEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Author;

class AuthorForceDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Author $author,
        public ?int $userId = null
    ) {}
}


