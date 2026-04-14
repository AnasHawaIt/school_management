<?php


namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\CategoryCreated;

class BusCreatedBroadcastEventListener
{

    public function handle(CategoryCreated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
