<?php


namespace Modules\Library\Events\CategoryEvents;

use Modules\Library\Entities\Category;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CategoryRestored
{
    use Dispatchable, SerializesModels;

    public Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}
