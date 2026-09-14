<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\Events\BookCopiesEvents\BookCopyDamaged;

class BookCopyDamagedLogEventListener
{
    public function handle(BookCopyDamaged $event): void
    {
        Log::warning('Library book copy marked as damaged.', [
            'copy_id' => $event->copy->id,
            'book_id' => $event->copy->book_id,
            'barcode' => $event->copy->barcode,
            'user_id' => $event->userId,
        ]);
    }
}
