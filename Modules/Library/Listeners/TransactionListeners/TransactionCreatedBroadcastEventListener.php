<?php


namespace Modules\Library\Listeners\TransactionListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;

class TransactionCreatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BorrowingCreated $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
