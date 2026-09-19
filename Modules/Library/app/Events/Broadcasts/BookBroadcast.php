<?php

namespace Modules\Library\app\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    public function broadcastOn()
    {
        return new Channel('books');
    }

}
