<?php

namespace Modules\Library\Notifications;

use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Notifications\Services\NotificationService;

class CreateBorrowingRejectedNotification
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(BorrowingApproved $event): void
    {
        $borrowing = $event->borrowing;

        $this->notificationService->create(
            user: $borrowing->user,
            type: 'borrowing.approved',
            title: 'تم رفض طلب الاستعارة',
            body: sprintf(
                'تم رفض طلب استعارة الكتاب "%s".',
                $borrowing->book->title
            ),
            data: [
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ],
        );
    }
}
