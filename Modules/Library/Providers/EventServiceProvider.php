<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Authors
use Modules\Library\Events\AuthorEvents\AuthorCreated;
use Modules\Library\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\Events\AuthorEvents\AuthorUpdated;

// Books
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookUpdated;

// Borrowings
use Modules\Library\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;

// Categories
use Modules\Library\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdateed;
use Modules\Library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\Events\CategoryEvents\CategoryUpdated;

// Members
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Events\MemberEvents\MemberUpdated;

// Publishers
use Modules\Library\Events\PublishersEvents\PublishersCreated;
use Modules\Library\Events\PublishersEvents\PublishersDeleted;
use Modules\Library\Events\PublishersEvents\PublishersUpdated;

// Author Listeners
use Modules\Library\Listeners\AuthorListeners\AuthorCreatedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorUpdateLogEventListener;

// Book Listeners
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedLogEventListener;

use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;

use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;

// Borrowing Listeners
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingApproved;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingCancelled;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingCreated;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingLost;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingOverdue;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingPickedUp;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingRejected;

use Modules\Library\Listeners\BorrowingListeners\LogBorrowingReturned;
use Modules\Library\Listeners\BorrowingListeners\LogBorrowingUpdated;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingApprovedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingCreatedNotification;

// Category Listeners
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingOverdueNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingRejectedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingReturnedNotification;
use Modules\Library\Listeners\CategoryListeners\CategoryCreatedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryDeletedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryUpdateLogEventListener;

// Member Listeners
use Modules\Library\Listeners\MemberListeners\MemberCreatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedLogEventListener;

// Publisher Listeners
use Modules\Library\Listeners\PublishersListeners\PublishersCreatedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersDeletedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersUpdateLogEventListener;


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

        BorrowingUpdateed::class=>[
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
