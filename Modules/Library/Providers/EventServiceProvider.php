<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingApprovedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingCreatedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingOverdueNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingRejectedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingReturnedNotification;
use Modules\Library\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        MemberCreated::class => [
            MemberCreatedNotificationDatabaseListener::class,
        ],

        MemberDeleted::class => [
            MemberDeletedNotificationDatabaseListener::class,
        ],

        BookCreated::class => [
            BookCreatedBroadcastEventListener::class,
        ],

        BookUpdated::class => [
            BookUpdatedNotificationDatabaseListener::class,
            BookUpdatedBroadcastEventListener::class,
        ],

        BookDeleted::class => [
            BookDeletedNotificationDatabaseListener::class,
            BookDeletedBroadcastEventListener::class,
        ],

        BorrowingCreated::class => [
            SendBorrowingCreatedNotification::class,
        ],

        BorrowingApproved::class => [
            SendBorrowingApprovedNotification::class,
        ],

        BorrowingRejected::class => [
            SendBorrowingRejectedNotification::class,
        ],

        BorrowingCancelled::class => [],

        BorrowingLost::class => [],

        BorrowingUpdated::class => [],

        BorrowingReturned::class => [
            SendBorrowingReturnedNotification::class
        ],

        BorrowingPickedUp::class => [],

        BorrowingOverdue::class => [
            SendBorrowingOverdueNotification::class
        ],

    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void
    {
    }
}
