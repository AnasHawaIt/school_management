<?php

namespace Modules\SMS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\SMS\Listeners\SendAnnouncementCreate;
use Modules\SMS\Listeners\SendAnnouncementDelete;
use Modules\SMS\Listeners\SendAnnouncementUpdete;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

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
