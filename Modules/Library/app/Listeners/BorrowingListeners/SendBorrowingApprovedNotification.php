<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Notifications\Services\NotificationService;

class SendBorrowingApprovedNotification implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingApproved $event): void
    {
        $borrowing = $event->borrowing;

        $user = $borrowing->member?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'أذن الحصول على كتاب ',
            body: 'لديك موافقة الحصول على  كتاب  .',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Approved',
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ]
        );
    }
}
