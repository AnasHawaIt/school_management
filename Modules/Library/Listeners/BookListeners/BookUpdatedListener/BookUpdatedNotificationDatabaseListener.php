<?php

namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Notifications\BookUpdatedNotification;

class BookUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(BookUpdated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookUpdatedNotification($event->book));
        }
    }
}
