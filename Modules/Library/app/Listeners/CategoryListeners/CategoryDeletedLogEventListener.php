<?php

namespace Modules\Library\app\Listeners\CategoryListeners;

use Modules\Library\app\Events\CategoryEvents\CategoryDeleted;

class CategoryDeletedLogEventListener
{
    public function handle(CategoryDeleted $event)
    {

        $category = $event->category;

        activity()
            ->causedBy($event->userId)
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.deleted');
    }
}
