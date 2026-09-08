<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Notifications\Services\NotificationService;

class SendBorrowingOverdueNotification
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingOverdue $event): void
    {
        $borrowing = $event->borrowing;

        $user = $borrowing->member?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'كتاب متأخر',
            body: 'لديك كتاب متأخر عن موعد الإعادة.',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'overdue',
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ]
        );
    }
}
