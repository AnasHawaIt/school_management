<?php


namespace Modules\Library\Listeners\FineListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\FinesEvents\FinePaid;
use Modules\Library\Events\FinesEvents\FinePaidBroadcast;

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
