<?php


namespace Modules\Library\Listeners\TransactionListeners;


use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\TransactionEvents\TransactionCreated;

class TransactionCreatedBroadcastEventListener
{

    public function handle(TransactionCreated $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
