<?php


namespace Modules\Library\app\Listeners\FineListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\FinesEvents\FinePaid;
use Modules\Library\app\Events\FinesEvents\FinePaidBroadcast;

class BroadcastFinePaid implements ShouldQueue
{
    public function handle(FinePaid $event): void
    {
        event(
            new FinePaidBroadcast(
                $event->fine
            )
        );
    }
}
