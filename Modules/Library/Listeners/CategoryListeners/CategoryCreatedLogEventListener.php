<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Events\CategoryEvents\CategoryCreated;

class CategoryCreatedLogEventListener
{
    public function handle(CategoryCreated $event)
    {
        $category = $event->category;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.created');
    }
}
