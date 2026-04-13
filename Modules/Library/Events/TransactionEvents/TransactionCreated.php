<?php

namespace Modules\Library\Events\TransactionEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Transaction;


class   TransactionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Transaction $transaction,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}

