<?php

namespace Modules\Library\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;

class TransactionBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Borrowing $transaction,
        public string $action = 'updated'
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('library.transactions'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'transaction.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'member_id' => $this->transaction->member_id,
                'book_id' => $this->transaction->book_id,
                'copy_id' => $this->transaction->copy_id,
                'status' => $this->transaction->status?->value,
                'borrow_date' => $this->transaction->borrow_date?->toDateString(),
                'due_date' => $this->transaction->due_date?->toDateString(),
                'return_date' => $this->transaction->return_date?->toDateString(),
            ],
            'action' => $this->action,
        ];
    }
}
