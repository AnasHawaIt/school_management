<?php


namespace Modules\Transport\app\Listeners\BusListeners\BusDeletedListeners;


use Modules\Transport\app\Events\Broadcasts\BusBroadcast;
use Modules\Transport\app\Events\BusEvents\BusDeleted;

class BusDeletedBroadcastEventListener
{

    public function handle(BusDeleted $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
