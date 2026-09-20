<?php

namespace Modules\Library\app\Events\CategoryEvents;

use Modules\Library\app\Entities\Category;

class CategoryDeleted
{
    public function __construct(
        public Category $category,
        public ?int $userId = null
    ) {}
}


