<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Events\CategoryEvents\CategoryForceDeleted;

class CategoryForceDeletedLogEventListener
{
    public function handle(CategoryForceDeleted $event)
    {

        $category = $event->category;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.forceDeleted');
    }
}
