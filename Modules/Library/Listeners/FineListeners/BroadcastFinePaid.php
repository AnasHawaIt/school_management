<?php


namespace Modules\Library\Listeners\FineListeners;


use Modules\Library\Events\FinesEvents\FinePaid;
use Modules\Library\Events\FinesEvents\FinePaidBroadcast;

class BroadcastFinePaid
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
