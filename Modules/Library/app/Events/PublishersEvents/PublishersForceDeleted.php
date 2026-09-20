<?php

namespace Modules\Library\app\Events\PublishersEvents;

use Modules\Library\app\Entities\Publisher;

class PublishersForceDeleted
{
    public function __construct(
        public Publisher $publisher,
        public ?int      $userId = null
    ) {}
}


