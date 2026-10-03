<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyDeleted;
use Modules\Notifications\Services\NotificationService;

class BookCopyDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCopyDeleted $event): void
    {
        $copy = $event->copy;

        $this->notificationService->sendToAll(
            title: 'حذف نسخة كتاب',
            body: "تم حذف نسخة الكتاب ذات الباركود: {$copy->barcode}",
            type: 'Library',
            data: [
                'entity' => 'BookCopy',
                'action' => 'Delete',
                'copy_id' => $copy->id,
                'book_id' => $copy->book_id,
                'barcode' => $copy->barcode,
            ]
        );
    }
}
