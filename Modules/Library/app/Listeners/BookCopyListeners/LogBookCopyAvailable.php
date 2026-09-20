<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyAvailable;

class LogBookCopyAvailable
{
    public function handle(BookCopyAvailable $event): void
    {
        Log::info('Library book copy became available.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
        ]);
    }
}
