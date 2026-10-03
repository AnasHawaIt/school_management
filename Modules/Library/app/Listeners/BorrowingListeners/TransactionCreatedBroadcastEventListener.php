<?php


namespace Modules\Library\Listeners\BorrowingListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;

class TransactionCreatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BorrowingRejected $event)
    {
        broadcast(new TransactionBroadcast($event->borrowing))->toOthers();
    }
}
