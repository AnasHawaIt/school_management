<?php

namespace Modules\Library\app\Events\FinesEvents;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Fine;

class FinePaidBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Fine $fine
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'library.fines.' . $this->fine->id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'fine.paid';
    }

    public function broadcastWith(): array
    {
        return [
            'fine_id' => $this->fine->id,
            'transaction_id' => $this->fine->transaction_id,
            'amount' => (string) $this->fine->amount,
            'status' => $this->fine->status,
            'paid_at' => $this->fine->paid_at?->toISOString(),
            'paid_by' => $this->fine->paid_by,
        ];
    }
}
