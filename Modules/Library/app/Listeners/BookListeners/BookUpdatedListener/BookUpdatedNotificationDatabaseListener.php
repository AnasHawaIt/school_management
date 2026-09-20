<?php

namespace Modules\Library\app\Listeners\BookListeners\BookUpdatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookUpdated;
use Modules\Notifications\app\Services\NotificationService;

class BookUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
    protected NotificationService $notificationService
    ) {
    }

        public function handle(BookUpdated $event): void
    {
        $book = $event->book;

        $this->notificationService->sendToAll(
            title: 'تحديث كتاب ',
            body: "تمت تحديث كتاب : {$book->title}",
            type: 'Library',
            data: [
                'entity' => 'Book',
                'action' => 'Delete',
                'book_id' => $book->id,
            ]
        );
    }
}
