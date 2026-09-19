<?php

namespace Modules\Library\app\Events\Broadcasts;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Borrowing;

class TransactionBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Borrowing $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new Channel('transactions');
    }

}
