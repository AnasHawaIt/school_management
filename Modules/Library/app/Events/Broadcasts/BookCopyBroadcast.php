<?php

namespace Modules\Library\Events\Broadcasts;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\BookCopy;

class BookCopyBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public BookCopy $copy
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'library.book.' . $this->copy->book_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'book-copy.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'copy' => [
                'id' => $this->copy->id,
                'book_id' => $this->copy->book_id,
                'barcode' => $this->copy->barcode,
                'status' => $this->copy->status?->value,
                'location' => $this->copy->location,
                'replacement_cost' => $this->copy->replacement_cost,
            ],
        ];
    }
}
