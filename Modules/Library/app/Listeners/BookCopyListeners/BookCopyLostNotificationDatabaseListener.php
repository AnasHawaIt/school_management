<?php

namespace Modules\Library\app\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookCopiesEvents\BookCopyLost;
use Modules\Notifications\app\Services\NotificationService;

class BookCopyLostNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCopyLost $event): void
    {
        $copy = $event->copy;

        $this->notificationService->sendToAll(
            title: 'فقدان نسخة كتاب',
            body: "تم تسجيل نسخة الكتاب ذات الباركود {$copy->barcode} كمفقودة.",
            type: 'Library',
            data: [
                'entity' => 'BookCopy',
                'action' => 'Lost',
                'copy_id' => $copy->id,
                'book_id' => $copy->book_id,
                'barcode' => $copy->barcode,
                'status' => 'lost',
            ]
        );
    }
}
