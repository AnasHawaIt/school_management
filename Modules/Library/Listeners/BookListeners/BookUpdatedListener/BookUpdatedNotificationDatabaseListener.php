<?php

namespace Modules\Library\Listeners\BookListeners\BookUpdatedListener;


use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Notifications\BookUpdatedNotification;

class BookUpdatedNotificationDatabaseListener
{
    public function handle(BookUpdated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookUpdatedNotification($event->book));
        }
    }
}
