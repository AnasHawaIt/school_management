<?php

namespace Modules\Library\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\Events\AuthorEvents\AuthorCreated;
use Modules\Library\Events\AuthorEvents\AuthorDeleted;
use Modules\Library\Events\AuthorEvents\AuthorForceDeleted;
use Modules\Library\Events\AuthorEvents\AuthorRestored;
use Modules\Library\Events\AuthorEvents\AuthorUpdated;
use Modules\Library\Events\BookCopiesEvents\BookCopyAvailable;
use Modules\Library\Events\BookCopiesEvents\BookCopyCreated;
use Modules\Library\Events\BookCopiesEvents\BookCopyDamaged;
use Modules\Library\Events\BookCopiesEvents\BookCopyDeleted;
use Modules\Library\Events\BookCopiesEvents\BookCopyForceDeleted;
use Modules\Library\Events\BookCopiesEvents\BookCopyLost;
use Modules\Library\Events\BookCopiesEvents\BookCopyRestored;
use Modules\Library\Events\BookCopiesEvents\BookCopyUpdated;
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
use Modules\Library\Events\BorrowingEvents\BorrowingRestored;
use Modules\Library\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\Events\CategoryEvents\CategoryCreated;
use Modules\Library\Events\CategoryEvents\CategoryDeleted;
use Modules\Library\Events\CategoryEvents\CategoryForceDeleted;
use Modules\Library\Events\CategoryEvents\CategoryRestored;
use Modules\Library\Events\CategoryEvents\CategoryUpdated;
use Modules\Library\Events\FinesEvents\FineCreated;
use Modules\Library\Events\FinesEvents\FinePaid;
use Modules\Library\Events\FinesEvents\FineWaived;
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
use Modules\Library\Events\ReservationEvents\ReservationCancelled;
use Modules\Library\Events\ReservationEvents\ReservationCreated;
use Modules\Library\Events\ReservationEvents\ReservationExpired;
use Modules\Library\Events\ReservationEvents\ReservationFulfilled;
use Modules\Library\Events\ReservationEvents\ReservationNotified;
use Modules\Library\Listeners\AuthorListeners\AuthorCreatedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorForceDeletedLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorRestoredLogEventListener;
use Modules\Library\Listeners\AuthorListeners\AuthorUpdateLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyAvailableNotificationListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyCreatedBroadcastEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyCreatedLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDamagedBroadcastEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDamagedLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDamagedNotificationDatabaseListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDeletedBroadcastEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDeletedLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyDeletedNotificationDatabaseListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyForceDeletedLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyLostBroadcastEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyLostLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyLostNotificationDatabaseListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyRestoredLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyUpdatedBroadcastEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyUpdatedLogEventListener;
use Modules\Library\Listeners\BookCopyListeners\BookCopyUpdatedNotificationDatabaseListener;
use Modules\Library\Listeners\BookCopyListeners\LogBookCopyAvailable;
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
use Modules\Library\Listeners\BorrowingListeners\CreateOverdueFineListener;
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
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingRestored;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingReturned;
use Modules\Library\Listeners\BorrowingListeners\Logs\LogBorrowingUpdated;
use Modules\Library\Listeners\BorrowingListeners\ProcessReservationQueueListener;
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
use Modules\Library\Listeners\FineListeners\BroadcastFinePaid;
use Modules\Library\Listeners\FineListeners\LogFineCreated;
use Modules\Library\Listeners\FineListeners\LogFinePaid;
use Modules\Library\Listeners\FineListeners\LogFineWaived;
use Modules\Library\Listeners\FineListeners\NotifyFineCreated;
use Modules\Library\Listeners\FineListeners\NotifyFinePaid;
use Modules\Library\Listeners\FineListeners\NotifyFineWaived;
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
use Modules\Library\Listeners\ReservationListeners\ProcessBookReservations;
use Modules\Library\Listeners\ReservationListeners\ProcessNextReservation;
use Modules\Library\Listeners\ReservationListeners\ReservationCancelledLogEventListener;
use Modules\Library\Listeners\ReservationListeners\ReservationCreatedLogEventListener;
use Modules\Library\Listeners\ReservationListeners\ReservationExpiredLogEventListener;
use Modules\Library\Listeners\ReservationListeners\ReservationFulfilledLogEventListener;
use Modules\Library\Listeners\ReservationListeners\ReservationNotifiedLogEventListener;
use Modules\Library\Listeners\ReservationListeners\SendReservationNotification;

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
            ProcessBookReservations::class,
            LogBookAvailable::class
        ],
        BookCopyCreated::class => [
            BookCopyCreatedLogEventListener::class,
            BookCopyCreatedBroadcastEventListener::class,
        ],

        BookCopyUpdated::class => [
            BookCopyUpdatedLogEventListener::class,
            BookCopyUpdatedNotificationDatabaseListener::class,
            BookCopyUpdatedBroadcastEventListener::class,
        ],

        BookCopyDeleted::class => [
            BookCopyDeletedLogEventListener::class,
            BookCopyDeletedNotificationDatabaseListener::class,
            BookCopyDeletedBroadcastEventListener::class,
        ],

        BookCopyRestored::class => [
            BookCopyRestoredLogEventListener::class,
        ],

        BookCopyForceDeleted::class => [
            BookCopyForceDeletedLogEventListener::class,
        ],

        BookCopyAvailable::class => [
            BookCopyAvailableNotificationListener::class,
            ProcessReservationQueueListener::class,
            LogBookCopyAvailable::class,
        ],

        BookCopyLost::class => [
            BookCopyLostLogEventListener::class,
            BookCopyLostNotificationDatabaseListener::class,
            BookCopyLostBroadcastEventListener::class,
        ],

        BookCopyDamaged::class => [
            BookCopyDamagedLogEventListener::class,
            BookCopyDamagedNotificationDatabaseListener::class,
            BookCopyDamagedBroadcastEventListener::class,
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
            CreateOverdueFineListener::class,
            SendBorrowingOverdueNotification::class,
        ],

        BorrowingRestored::class=>[
            LogBorrowingRestored::class,
        ],

        BorrowingDeleted::class => [
            LogBorrowingDeleted::class,
        ],

        BorrowingForceDeleted::class => [
            LogBorrowingForceDeleted::class,
        ],

        FineCreated::class => [
            LogFineCreated::class,
            NotifyFineCreated::class,
        ],

        FinePaid::class => [
            LogFinePaid::class,
            NotifyFinePaid::class,
            BroadcastFinePaid::class,
        ],

        FineWaived::class => [
            LogFineWaived::class,
            NotifyFineWaived::class,
        ],

        ReservationCancelled::class => [
            ReservationCancelledLogEventListener::class,
        ],

        ReservationCreated::class => [
            ProcessNextReservation::class,
            ReservationCreatedLogEventListener::class,
        ],

        ReservationExpired::class => [
            ReservationExpiredLogEventListener::class,
        ],

        ReservationFulfilled::class => [
            ReservationFulfilledLogEventListener::class,
        ],

        ReservationNotified::class => [
            ReservationNotifiedLogEventListener::class,
            SendReservationNotification::class,
        ],

    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void
    {
    }
}
