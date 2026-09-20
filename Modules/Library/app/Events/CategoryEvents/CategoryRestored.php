<?php


namespace Modules\Library\app\Events\CategoryEvents;

use Modules\Library\app\Entities\Category;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CategoryRestored
{
    use Dispatchable, SerializesModels;

    public Category $category;
    public function __construct(Category $category, public ?int $userId = null)
    {
        $this->category = $category;
    }
}
