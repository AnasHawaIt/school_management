<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Events\CategoryEvents\CategoryUpdated;

class CategoryUpdateLogEventListener
{
    public function handle(CategoryUpdated $event)
    {

        $category = $event->category;

        activity()
            ->causedBy($event->userId)
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.updated');
    }
}
