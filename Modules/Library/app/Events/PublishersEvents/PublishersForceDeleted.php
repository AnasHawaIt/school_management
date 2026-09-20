<?php

namespace Modules\Library\Events\PublishersEvents;

use Modules\Library\Entities\Publisher;

class PublishersForceDeleted
{
    public function __construct(
        public Publisher $publisher,
        public ?int      $userId = null
    ) {}
}


