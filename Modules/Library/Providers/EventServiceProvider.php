<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\app\Events\AuthorEvents\AuthorCreated;
use Modules\Library\app\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\app\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\app\Events\BookEvents\BookCreated;
use Modules\Library\app\Events\BookEvents\BookDeleted;
use Modules\Library\app\Events\BookEvents\BookUpdated;
use Modules\Library\app\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\app\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\app\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\app\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\app\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\app\Events\CategoryEvents\CategoryCreated;
use Modules\Library\app\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\app\Events\CategoryEvents\CategoryUpdated;
use Modules\Library\app\Events\MemberEvents\MemberCreated;
use Modules\Library\app\Events\MemberEvents\MemberDeleted;
use Modules\Library\app\Events\MemberEvents\MemberUpdated;
use Modules\Library\app\Events\PublishersEvents\PublishersCreated;
use Modules\Library\app\Events\PublishersEvents\PublishersDeleted;
use Modules\Library\app\Events\PublishersEvents\PublishersUpdated;
use Modules\Library\app\Listeners\AuthorListeners\AuthorCreatedLogEventListener;
use Modules\Library\app\Listeners\AuthorListeners\AuthorDeletedLogEventListener;
use Modules\Library\app\Listeners\AuthorListeners\AuthorUpdateLogEventListener;
use Modules\Library\app\Listeners\BookListeners\BookCreatedListener\BookCreatedBroadcastEventListener;
use Modules\Library\app\Listeners\BookListeners\BookCreatedListener\BookCreatedLogEventListener;
use Modules\Library\app\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\app\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\app\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;
use Modules\Library\app\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\app\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Library\app\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingApproved;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingCancelled;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingCreated;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingLost;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingOverdue;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingPickedUp;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingRejected;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingReturned;
use Modules\Library\app\Listeners\BorrowingListeners\Logs\LogBorrowingUpdated;
use Modules\Library\app\Listeners\BorrowingListeners\SendBorrowingApprovedNotification;
use Modules\Library\app\Listeners\BorrowingListeners\SendBorrowingCreatedNotification;
use Modules\Library\app\Listeners\BorrowingListeners\SendBorrowingOverdueNotification;
use Modules\Library\app\Listeners\BorrowingListeners\SendBorrowingRejectedNotification;
use Modules\Library\app\Listeners\BorrowingListeners\SendBorrowingReturnedNotification;
use Modules\Library\app\Listeners\CategoryListeners\CategoryCreatedLogEventListener;
use Modules\Library\app\Listeners\CategoryListeners\CategoryDeletedLogEventListener;
use Modules\Library\app\Listeners\CategoryListeners\CategoryUpdateLogEventListener;
use Modules\Library\app\Listeners\MemberListeners\MemberCreatedLogEventListener;
use Modules\Library\app\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\app\Listeners\MemberListeners\MemberDeletedLogEventListener;
use Modules\Library\app\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;
use Modules\Library\app\Listeners\MemberListeners\MemberUpdatedLogEventListener;
use Modules\Library\app\Listeners\PublishersListeners\PublishersCreatedLogEventListener;
use Modules\Library\app\Listeners\PublishersListeners\PublishersDeletedLogEventListener;
use Modules\Library\app\Listeners\PublishersListeners\PublishersUpdateLogEventListener;

// Authors

// Books

// Borrowings

// Categories

// Members

// Publishers

// Author Listeners

// Book Listeners

// Category Listeners

// Member Listeners

// Publisher Listeners

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        AuthorCreated::class => [
            AuthorCreatedLogEventListener::class,
        ],

        AuthorUpdated::class => [
            AuthorUpdateLogEventListener::class,
        ],

        AuthorDeleted::class => [
            AuthorDeletedLogEventListener::class,
        ],

        PublishersCreated::class => [
            PublishersCreatedLogEventListener::class,
        ],

        PublishersUpdated::class => [
            PublishersUpdateLogEventListener::class,
        ],

        PublishersDeleted::class => [
            PublishersDeletedLogEventListener::class,
        ],


        CategoryCreated::class => [
            CategoryCreatedLogEventListener::class,
        ],

        CategoryUpdated::class => [
            CategoryUpdateLogEventListener::class,
        ],

        CategoryDeleted::class => [
            CategoryDeletedLogEventListener::class,
        ],


        MemberCreated::class => [
            MemberCreatedLogEventListener::class,
            MemberCreatedNotificationDatabaseListener::class,
        ],

        MemberUpdated::class => [
            MemberUpdatedLogEventListener::class,
        ],

        MemberDeleted::class => [
            MemberDeletedLogEventListener::class,
            MemberDeletedNotificationDatabaseListener::class,
        ],


        BookCreated::class => [
            BookCreatedLogEventListener::class,
            BookCreatedBroadcastEventListener::class,
        ],

        BookUpdated::class => [
            BookUpdatedLogEventListener::class,
            BookUpdatedNotificationDatabaseListener::class,
            BookUpdatedBroadcastEventListener::class,
        ],

        BookDeleted::class => [
            BookDeletedLogEventListener::class,
            BookDeletedNotificationDatabaseListener::class,
            BookDeletedBroadcastEventListener::class,
        ],


        BorrowingCreated::class => [
            LogBorrowingCreated::class,
            SendBorrowingCreatedNotification::class,
        ],

        BorrowingApproved::class => [
            LogBorrowingApproved::class,
            SendBorrowingApprovedNotification::class,
        ],

        BorrowingRejected::class => [
            LogBorrowingRejected::class,
            SendBorrowingRejectedNotification::class,
        ],

        BorrowingCancelled::class=>[
            LogBorrowingCancelled::class,
            ],

        BorrowingLost::class=>[
            LogBorrowingLost::class,
        ],

        BorrowingUpdated::class=>[
            LogBorrowingUpdated::class,
        ],

        BorrowingReturned::class=>[
            LogBorrowingReturned::class,
            SendBorrowingReturnedNotification::class
        ],

        BorrowingPickedUp::class=>[
            LogBorrowingPickedUp::class
        ],

        BorrowingOverdue::class=>[
            LogBorrowingOverdue::class,
            SendBorrowingOverdueNotification::class
        ],


    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void
    {
    }
}
