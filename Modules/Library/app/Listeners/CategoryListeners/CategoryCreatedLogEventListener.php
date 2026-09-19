<?php

namespace Modules\Library\app\Listeners\CategoryListeners;

use Modules\Library\app\Events\CategoryEvents\CategoryCreated;

class CategoryCreatedLogEventListener
{
    public function handle(CategoryCreated $event)
    {
        $category = $event->category;

        activity()
            ->causedBy($event->userId)
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.created');
    }
}
