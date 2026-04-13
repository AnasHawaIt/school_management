<?php


namespace Modules\Library\Listeners\TransactionListeners;


use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\TransactionEvents\TransactionUpdated;

class TransactionUpdatedBroadcastEventListener
{

    public function handle(TransactionUpdated $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
