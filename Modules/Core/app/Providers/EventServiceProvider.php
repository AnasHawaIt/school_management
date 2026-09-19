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

use Modules\Core\app\Events\Role\RoleCreated;
use Modules\Core\app\Events\Role\RoleUpdated;
use Modules\Core\app\Events\Role\RoleDeleted;
use Modules\Core\app\Events\Role\RoleRestored;
use Modules\Core\app\Events\Role\RoleForceDeleted;
use Modules\Core\app\Events\Role\RolePermissionAttached;
use Modules\Core\app\Events\Role\RolePermissionDetached;
use Modules\Core\app\Events\Role\RolePermissionsSynced;

use Modules\Core\app\Listeners\Role\LogRoleCreated;
use Modules\Core\app\Listeners\Role\LogRoleUpdated;
use Modules\Core\app\Listeners\Role\LogRoleDeleted;
use Modules\Core\app\Listeners\Role\LogRoleRestored;
use Modules\Core\app\Listeners\Role\LogRoleForceDeleted;
use Modules\Core\app\Listeners\Role\LogRolePermissionAttached;
use Modules\Core\app\Listeners\Role\LogRolePermissionDetached;
use Modules\Core\app\Listeners\Role\LogRolePermissionsSynced;

use Modules\Core\app\Events\Permission\PermissionCreated;
use Modules\Core\app\Events\Permission\PermissionUpdated;
use Modules\Core\app\Events\Permission\PermissionDeleted;
use Modules\Core\app\Events\Permission\PermissionRestored;
use Modules\Core\app\Events\Permission\PermissionForceDeleted;

use Modules\Core\app\Listeners\Permission\LogPermissionCreated;
use Modules\Core\app\Listeners\Permission\LogPermissionUpdated;
use Modules\Core\app\Listeners\Permission\LogPermissionDeleted;
use Modules\Core\app\Listeners\Permission\LogPermissionRestored;
use Modules\Core\app\Listeners\Permission\LogPermissionForceDeleted;

use Modules\Core\app\Events\Setting\SettingCreated;
use Modules\Core\app\Events\Setting\SettingUpdated;
use Modules\Core\app\Events\Setting\SettingDeleted;
use Modules\Core\app\Events\Setting\SettingsUpdated;

use Modules\Core\app\Listeners\Setting\LogSettingCreated;
use Modules\Core\app\Listeners\Setting\LogSettingUpdated;
use Modules\Core\app\Listeners\Setting\LogSettingDeleted;
use Modules\Core\app\Listeners\Setting\LogSettingsUpdated;

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

       /*
       |--------------------------------------------------------------------------
       | Role
       |--------------------------------------------------------------------------
       */

       RoleCreated::class => [
           LogRoleCreated::class,
       ],

       RoleUpdated::class => [
           LogRoleUpdated::class,
       ],

       RoleDeleted::class => [
           LogRoleDeleted::class,
       ],

       RoleRestored::class => [
           LogRoleRestored::class,
       ],

       RoleForceDeleted::class => [
           LogRoleForceDeleted::class,
       ],

       RolePermissionAttached::class => [
           LogRolePermissionAttached::class,
       ],

       RolePermissionDetached::class => [
           LogRolePermissionDetached::class,
       ],

       RolePermissionsSynced::class => [
           LogRolePermissionsSynced::class,
       ],


       /*
       |--------------------------------------------------------------------------
       | Permission
       |--------------------------------------------------------------------------
       */

       PermissionCreated::class => [
           LogPermissionCreated::class,
       ],

       PermissionUpdated::class => [
           LogPermissionUpdated::class,
       ],

       PermissionDeleted::class => [
           LogPermissionDeleted::class,
       ],

       PermissionRestored::class => [
           LogPermissionRestored::class,
       ],

       PermissionForceDeleted::class => [
           LogPermissionForceDeleted::class,
       ],


        SettingCreated::class => [
            LogSettingCreated::class,
        ],

        SettingUpdated::class => [
            LogSettingUpdated::class,
        ],

        SettingDeleted::class => [
            LogSettingDeleted::class,
        ],

        SettingsUpdated::class => [
            LogSettingsUpdated::class,
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
