<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyUpdated;

class BookCopyUpdatedLogEventListener
{
    public function handle(BookCopyUpdated $event): void
    {
        Log::info('Library book copy updated.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
            'user_id' => $event->userId,
            'changes' => $event->changes,
        ]);
    }
}
