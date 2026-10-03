<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookCopiesEvents\BookCopyDamaged;
use Modules\Notifications\Services\NotificationService;

class BookCopyDamagedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCopyDamaged $event): void
    {
        $copy = $event->copy;

        $this->notificationService->sendToAll(
            title: 'تلف نسخة كتاب',
            body: "تم تسجيل نسخة الكتاب ذات الباركود {$copy->barcode} كتالفة.",
            type: 'Library',
            data: [
                'entity' => 'BookCopy',
                'action' => 'Damaged',
                'copy_id' => $copy->id,
                'book_id' => $copy->book_id,
                'barcode' => $copy->barcode,
                'status' => 'damaged',
            ]
        );
    }
}
