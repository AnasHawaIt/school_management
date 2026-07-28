<?php

namespace Modules\Library\Notifications;

use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Notifications\Services\NotificationService;

class CreateBorrowingApprovedNotification
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
            title: 'تمت الموافقة على طلب الاستعارة',
            body: sprintf(
                'تمت الموافقة على طلب استعارة الكتاب "%s".',
                $borrowing->book->title
            ),
            data: [
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ],
        );
    }
}
