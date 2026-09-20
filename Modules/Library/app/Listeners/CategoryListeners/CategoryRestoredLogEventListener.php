<?php

namespace Modules\Library\Listeners\CategoryListeners;

use Modules\Library\Events\CategoryEvents\CategoryRestored;

class CategoryRestoredLogEventListener
{
    public function handle(CategoryRestored $event)
    {

        $category = $event->category;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->withProperties([
                'category_id' => $category->id,
            ])
            ->log('category.restored');
    }
}
