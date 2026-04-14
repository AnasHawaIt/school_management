<?php

namespace Modules\Transport\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Transport\Events\BusEvents\BusCreated;
use Modules\Transport\Events\BusEvents\BusDeleted;
use Modules\Transport\Events\BusEvents\BusUpdated;
use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Events\RouteEvents\RouteDeleted;
use Modules\Transport\Events\RouteEvents\RouteUpdated;
use Modules\Transport\Events\RouteStopEvents\RouteStopCreated;
use Modules\Transport\Events\RouteStopEvents\RouteStopDeleted;
use Modules\Transport\Events\RouteStopEvents\RouteStopUpdated;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;
use Modules\Transport\Listeners\BusListeners\BusCreatedListeners\BusCreatedBroadcastEventListener;
use Modules\Transport\Listeners\BusListeners\BusCreatedListeners\CategoryCreatedLogEventListener;
use Modules\Transport\Listeners\BusListeners\BusCreatedListeners\BusCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\BusListeners\BusDeletedListeners\BusDeletedBroadcastEventListener;
use Modules\Transport\Listeners\BusListeners\BusDeletedListeners\AuthorDeletedLogEventListener;
use Modules\Transport\Listeners\BusListeners\BusUpdateListeners\BusUpdateBroadcastEventListener;
use Modules\Transport\Listeners\BusListeners\BusUpdateListeners\CategoryUpdateLogEventListener;
use Modules\Transport\Listeners\RouteListeners\RouteCreatedLogEventListener;
use Modules\Transport\Listeners\RouteListeners\RouteCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\RouteListeners\RouteDeletedLogEventListener;
use Modules\Transport\Listeners\RouteListeners\RouteUpdatedLogEventListener;
use Modules\Transport\Listeners\RouteStopListeners\RouteStopCreatedLogEventListener;
use Modules\Transport\Listeners\RouteStopListeners\RouteStopDeletedLogEventListener;
use Modules\Transport\Listeners\RouteStopListeners\TransactionUpdateLogEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedBroadcastEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedLogEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener\SubscriptionDeletedNotificationDatabaseListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener\SubscriptionDeletedLogEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener\SubscriptionUpdatedBroadcastEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener\SubscriptionUpdatedLogEventListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        BusCreated::class => [
           BusCreatedNotificationDatabaseListener::class,
            BusCreatedBroadcastEventListener::class,
            CategoryCreatedLogEventListener::class,
        ],

        BusUpdated::class => [
            CategoryUpdateLogEventListener::class,
            BusUpdateBroadcastEventListener::class,
        ],

        BusDeleted::class => [
            BusDeletedBroadcastEventListener::class,
            AuthorDeletedLogEventListener::class,
        ],

        RouteCreated::class => [
            RouteCreatedLogEventListener::class,
            RouteCreatedNotificationDatabaseListener::class,
        ],

        RouteUpdated::class => [
            RouteUpdatedLogEventListener::class,
        ],

        RouteDeleted::class => [
            RouteDeletedLogEventListener::class,
        ],

        RouteStopCreated::class => [
            RouteStopCreatedLogEventListener::class,
        ],

        RouteStopUpdated::class => [
            TransactionUpdateLogEventListener::class,
        ],

        RouteStopDeleted::class => [
            RouteStopDeletedLogEventListener::class,
        ],

        SubscriptionCreated::class => [
            SubscriptionCreatedLogEventListener::class,
            SubscriptionCreatedNotificationDatabaseListener::class,
            SubscriptionCreatedBroadcastEventListener::class,
        ],

        SubscriptionUpdated::class=>[
            SubscriptionUpdatedLogEventListener::class,
            SubscriptionUpdatedBroadcastEventListener::class,
        ],

        SubscriptionDeleted::class => [
            SubscriptionDeletedLogEventListener::class,
            SubscriptionDeletedNotificationDatabaseListener::class,
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
