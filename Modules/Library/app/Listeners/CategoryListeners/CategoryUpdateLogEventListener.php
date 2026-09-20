<?php

namespace Modules\Library\app\Listeners\CategoryListeners;

use Modules\Library\app\Events\CategoryEvents\CategoryUpdated;

class CategoryUpdateLogEventListener
{
    public function handle(CategoryUpdated $event)
    {

        $category = $event->category;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.updated');
    }
}
