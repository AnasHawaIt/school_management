<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\Events\AuthorEvents\AuthorCreated;
use Modules\Library\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\Events\AuthorEvents\AuthorForceDeleted;
use Modules\Library\Events\AuthorEvents\AuthorRestored;
use Modules\Library\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Events\BookEvents\BookForceDeleted;
use Modules\Library\Events\BookEvents\BookRestored;
use Modules\Library\Events\BookEvents\BookUpdated;
use Modules\Library\Events\BorrowingEvents\BookAvailable;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingDeleted;
use Modules\Library\Events\BorrowingEvents\BorrowingForceDeleted;
use Modules\Library\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\Events\BorrowingEvents\BorrowingRenewed;
use Modules\Library\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\Events\CategoryEvents\CategoryForceDeleted;
use Modules\Library\Events\CategoryEvents\CategoryRestored;
use Modules\Library\Events\CategoryEvents\CategoryUpdated;
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Events\MemberEvents\MemberForceDeleted;
use Modules\Library\Events\MemberEvents\MemberRestored;
use Modules\Library\Events\MemberEvents\MemberUpdated;
use Modules\Library\Events\PublishersEvents\PublishersCreated;
use Modules\Library\Events\PublishersEvents\PublishersDeleted;
use Modules\Library\Events\PublishersEvents\PublishersForceDeleted;
use Modules\Library\Events\PublishersEvents\PublishersRestored;
use Modules\Library\Events\PublishersEvents\PublishersUpdated;
use Modules\Library\Listeners\AuthorListeners\AuthorCreatedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorForceDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorRestoredLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorUpdateLogEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookCreatedListener\BookCreatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookDeletedListener\BookDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\BookListeners\BookForceDeletedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookRestoredLogEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Library\Listeners\BookListeners\BookUpdatedListener\BookUpdatedNotificationDatabaseListener;
use Modules\Library\Listeners\BorrowingListeners\BookAvailableNotificationListener;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBookAvailable;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingApproved;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingCancelled;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingCreated;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingDeleted;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingForceDeleted;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingLost;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingOverdue;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingPickedUp;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingRejected;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingRenewed;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingReturned;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingUpdated;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingApprovedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingCreatedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingOverdueNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingRejectedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingRenewedNotification;
use Modules\Library\Listeners\BorrowingListeners\SendBorrowingReturnedNotification;
use Modules\Library\Listeners\CategoryListeners\CategoryCreatedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryDeletedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryForceDeletedLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryRestoredLogEventListener;
use Modules\Library\Listeners\CategoryListeners\CategoryUpdateLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberCreatedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\MemberListeners\MemberForceDeletedLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberRestoredLogEventListener;
use Modules\Library\Listeners\MemberListeners\MemberUpdatedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersCreatedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersDeletedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersForceDeletedLogEventListener;
use Modules\Library\Listeners\PublishersListeners\PublishersRestoredLogEventListener;
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

        AuthorRestored::class=>[
            AuthorRestoredLogEventListener::class
        ],

        AuthorForceDeleted::class=>[
            AuthorForceDeletedLogEventListener::class
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

        PublishersRestored::class => [
            PublishersRestoredLogEventListener::class
        ],

        PublishersForceDeleted::class => [
            PublishersForceDeletedLogEventListener::class,
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

        CategoryRestored::class => [
            CategoryRestoredLogEventListener::class
        ],

        CategoryForceDeleted::class => [
            CategoryForceDeletedLogEventListener::class
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

        MemberRestored::class => [
            MemberRestoredLogEventListener::class
        ],

        MemberForceDeleted::class => [
            MemberForceDeletedLogEventListener::class
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

        BookRestored::class=>[
            BookRestoredLogEventListener::class,
        ],

        BookForceDeleted::class => [
            BookForceDeletedLogEventListener::class
        ],

        BookAvailable::class => [
            BookAvailableNotificationListener::class,
            LogBookAvailable::class
        ],

        BorrowingCreated::class => [
            LogBorrowingCreated::class,
            SendBorrowingCreatedNotification::class,
        ],

        BorrowingRenewed::class => [
            LogBorrowingRenewed::class,
            SendBorrowingRenewedNotification::class,
        ],

        BorrowingApproved::class => [
            LogBorrowingApproved::class,
            SendBorrowingApprovedNotification::class,
        ],

        BorrowingRejected::class => [
            LogBorrowingRejected::class,
            SendBorrowingRejectedNotification::class,
        ],

        BorrowingCancelled::class => [
            LogBorrowingCancelled::class,
        ],

        BorrowingLost::class => [
            LogBorrowingLost::class,
        ],

        BorrowingUpdated::class => [
            LogBorrowingUpdated::class,
        ],

        BorrowingReturned::class => [
            LogBorrowingReturned::class,
            SendBorrowingReturnedNotification::class,
        ],

        BorrowingPickedUp::class => [
            LogBorrowingPickedUp::class,
        ],

        BorrowingOverdue::class => [
            LogBorrowingOverdue::class,
            SendBorrowingOverdueNotification::class,
        ],

        BorrowingDeleted::class => [
            LogBorrowingDeleted::class,
        ],

        BorrowingForceDeleted::class => [
            LogBorrowingForceDeleted::class,
        ],

    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void
    {
    }
}
