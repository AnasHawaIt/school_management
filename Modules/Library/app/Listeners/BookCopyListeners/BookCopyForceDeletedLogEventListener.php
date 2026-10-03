<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\Events\BookCopiesEvents\BookCopyForceDeleted;

class BookCopyForceDeletedLogEventListener
{
    public function handle(BookCopyForceDeleted $event): void
    {
        Log::warning('Library book copy permanently deleted.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
            'user_id' => $event->userId,
        ]);
    }
}
