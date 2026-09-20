<?php


namespace Modules\Library\app\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Notifications\app\Services\NotificationService;

class SendBorrowingOverdueNotification implements ShouldQueue
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
