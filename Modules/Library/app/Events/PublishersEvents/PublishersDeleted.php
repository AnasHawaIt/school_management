<?php

namespace Modules\Library\app\Events\PublishersEvents;

use Modules\Library\app\Entities\Publishers;

class PublishersDeleted
{
    public function __construct(
        public Publishers $publisher,
        public ?int $userId = null
    ) {}
}


