<?php

namespace Modules\Library\app\Events\PublishersEvents;

use Modules\Library\app\Entities\Publisher;

class PublishersDeleted
{
    public function __construct(
        public Publisher $publisher,
        public ?int      $userId = null
    ) {}
}


