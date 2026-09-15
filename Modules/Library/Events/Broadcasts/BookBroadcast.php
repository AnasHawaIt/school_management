<?php

namespace Modules\Library\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Book;

class BookBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Book $book,
        public string $action = 'updated',
        public array $changes = []
    ) {
    }

    /**
     * Channel used by Laravel Echo.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('library.books'),
        ];
    }

    /**
     * Event name received by Echo.
     *
     * Echo:
     * .listen('.book.updated', ...)
     */
    public function broadcastAs(): string
    {
        return 'book.' . $this->action;
    }

    /**
     * Data sent to the browser.
     */
    public function broadcastWith(): array
    {
        return [
            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
            ],

            'action' => $this->action,

            'changes' => $this->changes,
        ];
    }
}
