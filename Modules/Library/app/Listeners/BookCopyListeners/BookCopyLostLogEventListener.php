<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyLost;

class BookCopyLostLogEventListener implements ShouldQueue
{
    public function handle(BookCopyLost $event): void
    {
        Log::warning('Library book copy marked as lost.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
            'user_id' => $event->userId,
        ]);
    }
}
