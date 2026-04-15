<?php

namespace Modules\Library\Events\PublishersEvents;

use Modules\Library\Entities\Publishers;

class PublishersDeleted
{
    public function __construct(
        public Publishers $publisher,
        public ?int $userId = null
    ) {}
}


