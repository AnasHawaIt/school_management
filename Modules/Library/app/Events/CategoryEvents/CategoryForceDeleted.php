<?php

namespace Modules\Library\app\Events\CategoryEvents;

use Modules\Library\app\Entities\Category;

class CategoryForceDeleted
{
    public function __construct(
        public Category $category,
        public ?int $userId = null
    ) {}
}


