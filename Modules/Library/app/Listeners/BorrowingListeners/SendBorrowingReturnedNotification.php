<?php


namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Events\BorrowingEvents\BorrowingReturned;
use Modules\Notifications\app\Services\NotificationService;

class SendBorrowingReturnedNotification
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingReturned $event): void
    {
        $borrowing = $event->borrowing;

        $user = $borrowing->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'اعادة  كتاب ',
            body: 'تمت اعادة الكتاب .',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Returned',
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ]
        );
    }
}
