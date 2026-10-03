<?php


namespace Modules\Transport\Listeners\BusListeners\BusDeletedListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\BusDeleted;

class BusDeletedBroadcastEventListener
{

    public function handle(BusDeleted $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
