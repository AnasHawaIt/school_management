<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyUpdated;
use Modules\Notifications\Services\NotificationService;

class BookCopyUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCopyUpdated $event): void
    {
        $copy = $event->copy;

        $this->notificationService->sendToAll(
            title: 'تحديث نسخة كتاب',
            body: "تم تحديث نسخة الكتاب ذات الباركود: {$copy->barcode}",
            type: 'Library',
            data: [
                'entity' => 'BookCopy',
                'action' => 'Update',
                'copy_id' => $copy->id,
                'book_id' => $copy->book_id,
                'barcode' => $copy->barcode,
                'status' => $copy->status?->value,
            ]
        );
    }
}
