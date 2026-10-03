<?php

namespace Modules\Library\Events\CategoryEvents;

use Modules\Library\Entities\Category;

class CategoryForceDeleted
{
    public function __construct(
        public Category $category,
        public ?int $userId = null
    ) {}
}


