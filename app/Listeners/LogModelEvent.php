<?php

namespace App\Listeners;

use App\Events\ModelEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogModelEvent implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ModelEvent $event): void
    {
        \Log::info("Model Event: {$event->model} {$event->action}", [
            'data' => $event->data,
            'user_id' => $event->userId,
            'time' => now(),
        ]);
    }
}
