<?php


namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
use Modules\Notifications\app\Services\NotificationService;

class SendBorrowingRejectedNotification
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingRejected $event): void
    {
        $borrowing = $event->borrowing;

        $user = $borrowing->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'طلب مرفوض  ',
            body: 'لديك كتاب مرفوض.',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Rejected',
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ]
        );
    }
}
