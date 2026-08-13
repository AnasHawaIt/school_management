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
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedNotificationDatabaseListener;

use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;

use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;

// Borrowing Listeners
use Modules\Library\Listeners\TransactionListeners\LogBorrowingApproved;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingCancelled;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingCreated;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingLost;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingOverdue;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingPickedUp;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingRejected;

use Modules\Library\Listeners\TransactionListeners\LogBorrowingReturned;
use Modules\Library\Listeners\TransactionListeners\TransactionCreatedNotificationDatabaseListener;

// Category Listeners
use Modules\Library\Listeners\CategoryListeners\CategoryCreatedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryDeletedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryUpdateLogEventListener;

// Member Listeners
use Modules\Library\Listeners\MemberListeners\MemberCreatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedNotificationDatabaseListener;

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
            MemberUpdatedNotificationDatabaseListener::class,
        ],

        MemberDeleted::class => [
            MemberDeletedLogEventListener::class,
            MemberDeletedNotificationDatabaseListener::class,
        ],


        BookCreated::class => [
            BookCreatedLogEventListener::class,
            BookCreatedNotificationDatabaseListener::class,
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
            TransactionCreatedNotificationDatabaseListener::class,
        ],

        BorrowingApproved::class => [
            LogBorrowingApproved::class,
        ],

        BorrowingRejected::class => [
            LogBorrowingRejected::class,
        ],

        BorrowingCancelled::class=>[
            LogBorrowingCancelled::class,
            ],

        BorrowingLost::class=>[
            LogBorrowingLost::class,
        ],

        BorrowingReturned::class=>[
            LogBorrowingReturned::class,
        ],

        BorrowingPickedUp::class=>[
            LogBorrowingPickedUp::class
        ],

        BorrowingOverdue::class=>[
            LogBorrowingOverdue::class
        ],


    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void
    {
    }
}
