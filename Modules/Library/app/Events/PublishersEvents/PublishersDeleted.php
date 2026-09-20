<?php

namespace Modules\Library\Events\PublishersEvents;

use Modules\Library\Entities\Publisher;

class PublishersDeleted
{
    public function __construct(
        public Publisher $publisher,
        public ?int      $userId = null
    ) {}
}


