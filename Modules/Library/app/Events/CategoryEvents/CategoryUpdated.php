<?php

namespace Modules\Library\app\Events\CategoryEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Category;


class CategoryUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
       public Category $category,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

