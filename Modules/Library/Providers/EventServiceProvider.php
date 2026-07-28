<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\Events\AuthorEvents\AuthorCreated;
use Modules\Library\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\Events\CategoryEvents\CategoryUpdated;
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Events\MemberEvents\MemberUpdated;
use Modules\Library\Events\PublishersEvents\PublishersCreated;
use Modules\Library\Events\PublishersEvents\PublishersDeleted;
use Modules\Library\Events\PublishersEvents\PublishersUpdated;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\Listeners\AuthorListeners\AuthorCreatedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorUpdateLogEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;
use Modules\Library\Listeners\Borrowing\CreateBorrowingApprovedNotification;
use Modules\Library\Listeners\CategoryListeners\CategoryCreatedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryDeletedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryUpdateLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedNotificationDatabaseListener;
use Modules\Library\Listeners\PublishersListeners\PublishersCreatedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersDeletedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersUpdateLogEventListener;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingApproved;
use Modules\Library\Listeners\TransactionListeners\LogBorrowingCreated;
use Modules\Library\Listeners\TransactionListeners\TransactionCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\TransactionListeners\TransactionDeletedBroadcastEventListener;
use Modules\Library\Listeners\TransactionListeners\TransactionDeletedLogEventListener;
use Modules\Library\Listeners\TransactionListeners\TransactionDeletedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
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

        PublishersCreated::class=>[
            PublishersCreatedLogEventListener::class
            ],

        PublishersUpdated::class=>[
            PublishersUpdateLogEventListener::class
        ],

        PublishersDeleted::class=>[
            PublishersDeletedLogEventListener::class
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
            CreateBorrowingApprovedNotification::class,
        ],

        BorrowingRejected::class => [
            TransactionDeletedLogEventListener::class,
            TransactionDeletedNotificationDatabaseListener::class,
            TransactionDeletedBroadcastEventListener::class,
        ]

    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
