<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\Events\BookCopiesEvents\BookCopyDeleted;

class BookCopyDeletedLogEventListener
{
    public function handle(BookCopyDeleted $event): void
    {
        Log::info('Library book copy deleted.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
            'user_id' => $event->userId,
        ]);
    }
}
