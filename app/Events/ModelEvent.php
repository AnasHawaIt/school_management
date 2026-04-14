<?php


namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $action, // created | updated | deleted
        public string $model,  // Bus, Route...
        public array  $data,
        public ?int   $userId = null
    )
    {
    }
}
