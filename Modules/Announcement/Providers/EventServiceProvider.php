<?php

namespace Modules\Announcement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener\AnnouncementCreatedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener\AnnouncementCreatedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener\AnnouncementCreatedNotificationDatabaseListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener\AnnouncementDeletedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener\AnnouncementDeletedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener\AnnouncementDeletedNotificationDatabaseListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener\AnnouncementUpdatedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener\AnnouncementUpdatedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener\AnnouncementUpdatedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        AnnouncementCreated::class => [
            AnnouncementCreatedLogEventListener::class,
            AnnouncementCreatedNotificationDatabaseListener::class,
            AnnouncementCreatedBroadcastEventListener::class,
        ],

        AnnouncementUpdated::class => [
            AnnouncementUpdatedLogEventListener::class,
            AnnouncementUpdatedNotificationDatabaseListener::class,
            AnnouncementUpdatedBroadcastEventListener::class,
        ],

        AnnouncementDeleted::class => [
            AnnouncementDeletedLogEventListener::class,
            AnnouncementDeletedNotificationDatabaseListener::class,
            AnnouncementDeletedBroadcastEventListener::class,
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
