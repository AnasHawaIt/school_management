<?php

namespace Modules\Announcement\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\app\Events\AnnouncementCreated;
use Modules\Announcement\app\Events\AnnouncementDeleted;
use Modules\Announcement\app\Events\AnnouncementExpired;
use Modules\Announcement\app\Events\AnnouncementPublished;
use Modules\Announcement\app\Events\AnnouncementRestored;
use Modules\Announcement\app\Events\AnnouncementScheduled;
use Modules\Announcement\app\Events\AnnouncementUpdated;
use Modules\Announcement\app\Listeners\AnnouncementCreatedListener\AnnouncementCreatedBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementCreatedListener\AnnouncementCreatedLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementDeletedListener\AnnouncementDeletedBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementDeletedListener\AnnouncementDeletedLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementExpiredListener\AnnouncementExpiredBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementExpiredListener\AnnouncementExpiredLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementPublishedListener\AnnouncementPublishedBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementPublishedListener\AnnouncementPublishedLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementPublishedListener\AnnouncementPublishedNotificationDatabaseListener;
use Modules\Announcement\app\Listeners\AnnouncementRestoredListener\AnnouncementRestoredBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementRestoredListener\AnnouncementRestoredLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementScheduledListener\AnnouncementScheduledBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementScheduledListener\AnnouncementScheduledLogEventListener;
use Modules\Announcement\app\Listeners\AnnouncementUpdatedListener\AnnouncementUpdatedBroadcastEventListener;
use Modules\Announcement\app\Listeners\AnnouncementUpdatedListener\AnnouncementUpdatedLogEventListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        /*
        |--------------------------------------------------------------------------
        | Announcement Created
        |--------------------------------------------------------------------------
        */
        AnnouncementCreated::class => [
            AnnouncementCreatedBroadcastEventListener::class,
            AnnouncementCreatedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Updated
        |--------------------------------------------------------------------------
        */
        AnnouncementUpdated::class => [
            AnnouncementUpdatedBroadcastEventListener::class,
            AnnouncementUpdatedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Deleted
        |--------------------------------------------------------------------------
        */
        AnnouncementDeleted::class => [
            AnnouncementDeletedBroadcastEventListener::class,
            AnnouncementDeletedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Restored
        |--------------------------------------------------------------------------
        */
        AnnouncementRestored::class => [
            AnnouncementRestoredBroadcastEventListener::class,
            AnnouncementRestoredLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Published
        |--------------------------------------------------------------------------
        */
        AnnouncementPublished::class => [
            AnnouncementPublishedBroadcastEventListener::class,
            AnnouncementPublishedLogEventListener::class,
            AnnouncementPublishedNotificationDatabaseListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Scheduled
        |--------------------------------------------------------------------------
        */
        AnnouncementScheduled::class => [
            AnnouncementScheduledBroadcastEventListener::class,
            AnnouncementScheduledLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Expired
        |--------------------------------------------------------------------------
        */
        AnnouncementExpired::class => [
            AnnouncementExpiredBroadcastEventListener::class,
            AnnouncementExpiredLogEventListener::class,
        ],

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
