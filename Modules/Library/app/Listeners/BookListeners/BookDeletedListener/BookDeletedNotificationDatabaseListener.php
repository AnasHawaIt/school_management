<?php

namespace Modules\Library\app\Listeners\BookListeners\BookDeletedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BookEvents\BookDeleted;
use Modules\Notifications\app\Services\NotificationService;

class BookDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookDeleted $event): void
    {
        $book = $event->book;

        $this->notificationService->sendToAll(
            title: 'كتاب محذوف',
            body: "تمت حذف كتاب : {$book->title}",
            type: 'Library',
            data: [
                'entity' => 'Book',
                'action' => 'Delete',
                'book' => $book,
            ]
        );
    }
}
