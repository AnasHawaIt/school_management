<?php

namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Notifications\BookDeletedNotification;

class BookDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(BookDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookDeletedNotification($event->book));
        }
    }
}
