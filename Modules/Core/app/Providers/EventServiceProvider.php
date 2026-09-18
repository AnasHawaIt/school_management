<?php

namespace Modules\Core\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Core\app\Events\Auth\TokenRefreshed;
use Modules\Core\app\Events\Auth\UserLoggedIn;
use Modules\Core\app\Events\Auth\UserLoggedOut;
use Modules\Core\app\Events\User\UserCreated;
use Modules\Core\app\Events\User\UserDeleted;
use Modules\Core\app\Events\User\UserForceDeleted;
use Modules\Core\app\Events\User\UserPasswordChanged;
use Modules\Core\app\Events\User\UserRestored;
use Modules\Core\app\Events\User\UserRoleAssigned;
use Modules\Core\app\Events\User\UserRoleRemoved;
use Modules\Core\app\Events\User\UserUpdated;
use Modules\Core\app\Listeners\Auth\LogTokenRefreshed;
use Modules\Core\app\Listeners\Auth\LogUserLoggedIn;
use Modules\Core\app\Listeners\Auth\LogUserLoggedOut;
use Modules\Core\app\Listeners\User\LogUserForceDeleted;
use Modules\Core\app\Listeners\User\LogUserPasswordChanged;
use Modules\Core\app\Listeners\User\LogUserRestored;
use Modules\Core\app\Listeners\User\NotifyPasswordChanged;
use Modules\Core\app\Listeners\User\UserCreated\BroadcastUserCreated;
use Modules\Core\app\Listeners\User\UserCreated\LogUserCreated;
use Modules\Core\app\Listeners\User\UserCreated\NotifyUserCreated;
use Modules\Core\app\Listeners\User\UserDeleted\BroadcastUserDeleted;
use Modules\Core\app\Listeners\User\UserDeleted\LogUserDeleted;
use Modules\Core\app\Listeners\User\UserRole\LogUserRoleAssigned;
use Modules\Core\app\Listeners\User\UserRole\LogUserRoleRemoved;
use Modules\Core\app\Listeners\User\UserRole\NotifyRoleAssigned;
use Modules\Core\app\Listeners\User\UserRole\NotifyRoleRemoved;
use Modules\Core\app\Listeners\User\UserUpdated\BroadcastUserUpdated;
use Modules\Core\app\Listeners\User\UserUpdated\LogUserUpdated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        TokenRefreshed::class => [
            LogTokenRefreshed::class,
        ],

        UserLoggedIn::class => [
            LogUserLoggedIn::class,
        ],

        UserLoggedOut::class => [
            LogUserLoggedOut::class,
        ],

        UserCreated::class => [
            LogUserCreated::class,
            NotifyUserCreated::class,
            BroadcastUserCreated::class,
        ],

        UserUpdated::class=>[
            LogUserUpdated::class,
            BroadcastUserUpdated::class,
        ],

        UserDeleted::class => [
            LogUserDeleted::class,
            BroadcastUserDeleted::class,
        ],

        UserForceDeleted::class => [
            LogUserForceDeleted::class,
        ],

        UserRestored::class=>[
            LogUserRestored::class,
        ],

        UserRoleAssigned::class => [
            LogUserRoleAssigned::class,
            NotifyRoleAssigned::class,
        ],

        UserRoleRemoved::class => [
            LogUserRoleRemoved::class,
            NotifyRoleRemoved::class,
        ],

        UserPasswordChanged::class => [
            LogUserPasswordChanged::class,
            NotifyPasswordChanged::class,
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
