<?php

namespace Modules\Transport\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Library\app\Listeners\BookListeners\BookDeletedListener\BookDeletedLogEventListener;
use Modules\Library\app\Listeners\BookListeners\BookUpdatedListener\BookUpdatedLogEventListener;
use Modules\Transport\app\Events\BusEvents\BusCreated;
use Modules\Transport\app\Events\BusEvents\BusDeleted;
use Modules\Transport\app\Events\BusEvents\BusStopStageChanged;
use Modules\Transport\app\Events\BusEvents\BusUpdated;
use Modules\Transport\app\Events\RouteEvents\RouteCreated;
use Modules\Transport\app\Events\RouteEvents\RouteDeleted;
use Modules\Transport\app\Events\RouteEvents\RouteUpdated;
use Modules\Transport\app\Events\RouteStopEvents\RouteStopCreated;
use Modules\Transport\app\Events\RouteStopEvents\RouteStopDeleted;
use Modules\Transport\app\Events\RouteStopEvents\RouteStopUpdated;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionCreated;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionUpdated;
use Modules\Transport\app\Listeners\BusListeners\BusCreatedListeners\BusCreatedBroadcastEventListener;
use Modules\Transport\app\Listeners\BusListeners\BusCreatedListeners\BusCreatedLogEventListener;
use Modules\Transport\app\Listeners\BusListeners\BusCreatedListeners\BusCreatedNotificationDatabaseListener;
use Modules\Transport\app\Listeners\BusListeners\BusDeletedListeners\BusDeletedBroadcastEventListener;
use Modules\Transport\app\Listeners\BusListeners\BusStopStageChangedListener;
use Modules\Transport\app\Listeners\BusListeners\BusUpdateListeners\BusUpdateBroadcastEventListener;
use Modules\Transport\app\Listeners\RouteListeners\RouteCreatedLogEventListener;
use Modules\Transport\app\Listeners\RouteListeners\RouteCreatedNotificationDatabaseListener;
use Modules\Transport\app\Listeners\RouteListeners\RouteDeletedLogEventListener;
use Modules\Transport\app\Listeners\RouteListeners\RouteUpdatedLogEventListener;
use Modules\Transport\app\Listeners\RouteStopListeners\RouteStopCreatedLogEventListener;
use Modules\Transport\app\Listeners\RouteStopListeners\RouteStopDeletedLogEventListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedBroadcastEventListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedLogEventListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedNotificationDatabaseListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionDeletedListener\SubscriptionDeletedLogEventListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionDeletedListener\SubscriptionDeletedNotificationDatabaseListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionUpdatedListener\SubscriptionUpdatedBroadcastEventListener;
use Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionUpdatedListener\SubscriptionUpdatedLogEventListener;


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

        ],

        BusUpdated::class => [
            BusUpdateBroadcastEventListener::class,
            BookUpdatedLogEventListener::class,
            BusCreatedLogEventListener::class,
        ],

        BusDeleted::class => [
            BusDeletedBroadcastEventListener::class,
            BookDeletedLogEventListener::class,
        ],

        BusStopStageChanged::class => [
            BusStopStageChangedListener::class,
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
            RouteUpdatedLogEventListener::class,
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
