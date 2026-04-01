<?php

namespace Modules\SMS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        \Modules\Announcement\Events\AnnouncementCreated::class => [
            \Modules\SMS\Listeners\SendAnnouncementCreate::class,
        ],
        \Modules\Announcement\Events\AnnouncementDeleted::class => [
            \Modules\SMS\Listeners\SendAnnouncementDelete::class,
        ],
        \Modules\Announcement\Events\AnnouncementUpdated::class => [
            \Modules\SMS\Listeners\SendAnnouncementUpdete::class,
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
