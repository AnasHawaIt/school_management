<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Core\Events\Auth\TokenRefreshed;
use Modules\Core\Events\Auth\UserLoggedIn;
use Modules\Core\Events\Auth\UserLoggedOut;
use Modules\Core\Events\Permission\PermissionCreated;
use Modules\Core\Events\Permission\PermissionDeleted;
use Modules\Core\Events\Permission\PermissionForceDeleted;
use Modules\Core\Events\Permission\PermissionRestored;
use Modules\Core\Events\Permission\PermissionUpdated;
use Modules\Core\Events\Role\RoleCreated;
use Modules\Core\Events\Role\RoleDeleted;
use Modules\Core\Events\Role\RoleForceDeleted;
use Modules\Core\Events\Role\RolePermissionAttached;
use Modules\Core\Events\Role\RolePermissionDetached;
use Modules\Core\Events\Role\RolePermissionsSynced;
use Modules\Core\Events\Role\RoleRestored;
use Modules\Core\Events\Role\RoleUpdated;
use Modules\Core\Events\Setting\SettingCreated;
use Modules\Core\Events\Setting\SettingDeleted;
use Modules\Core\Events\Setting\SettingsUpdated;
use Modules\Core\Events\Setting\SettingUpdated;
use Modules\Core\Events\User\UserCreated;
use Modules\Core\Events\User\UserDeleted;
use Modules\Core\Events\User\UserForceDeleted;
use Modules\Core\Events\User\UserPasswordChanged;
use Modules\Core\Events\User\UserRestored;
use Modules\Core\Events\User\UserRoleAssigned;
use Modules\Core\Events\User\UserRoleRemoved;
use Modules\Core\Events\User\UserUpdated;
use Modules\Core\Listeners\Auth\LogTokenRefreshed;
use Modules\Core\Listeners\Auth\LogUserLoggedIn;
use Modules\Core\Listeners\Auth\LogUserLoggedOut;
use Modules\Core\Listeners\Permission\LogPermissionCreated;
use Modules\Core\Listeners\Permission\LogPermissionDeleted;
use Modules\Core\Listeners\Permission\LogPermissionForceDeleted;
use Modules\Core\Listeners\Permission\LogPermissionRestored;
use Modules\Core\Listeners\Permission\LogPermissionUpdated;
use Modules\Core\Listeners\Role\create\BroadcastRoleCreated;
use Modules\Core\Listeners\Role\create\LogRoleCreated;
use Modules\Core\Listeners\Role\delete\BroadcastRoleDeleted;
use Modules\Core\Listeners\Role\delete\LogRoleDeleted;
use Modules\Core\Listeners\Role\delete\NotifyRoleDeleted;
use Modules\Core\Listeners\Role\LogRoleForceDeleted;
use Modules\Core\Listeners\Role\LogRoleRestored;
use Modules\Core\Listeners\Role\RolePermissionAttached\BroadcastRolePermissionChanged;
use Modules\Core\Listeners\Role\RolePermissionAttached\LogRolePermissionAttached;
use Modules\Core\Listeners\Role\RolePermissionAttached\LogRolePermissionDetached;
use Modules\Core\Listeners\Role\RolePermissionAttached\NotifyRolePermissionChanged;
use Modules\Core\Listeners\Role\RolePermissionsSynced\BroadcastRolePermissionsSynced;
use Modules\Core\Listeners\Role\RolePermissionsSynced\LogRolePermissionsSynced;
use Modules\Core\Listeners\Role\RolePermissionsSynced\NotifyRolePermissionsSynced;
use Modules\Core\Listeners\Role\update\BroadcastRoleUpdated;
use Modules\Core\Listeners\Role\update\LogRoleUpdated;
use Modules\Core\Listeners\Setting\LogSettingCreated;
use Modules\Core\Listeners\Setting\LogSettingDeleted;
use Modules\Core\Listeners\Setting\LogSettingsUpdated;
use Modules\Core\Listeners\Setting\LogSettingUpdated;
use Modules\Core\Listeners\User\LogUserForceDeleted;
use Modules\Core\Listeners\User\LogUserPasswordChanged;
use Modules\Core\Listeners\User\LogUserRestored;
use Modules\Core\Listeners\User\NotifyPasswordChanged;
use Modules\Core\Listeners\User\UserCreated\BroadcastUserCreated;
use Modules\Core\Listeners\User\UserCreated\LogUserCreated;
use Modules\Core\Listeners\User\UserCreated\NotifyUserCreated;
use Modules\Core\Listeners\User\UserDeleted\BroadcastUserDeleted;
use Modules\Core\Listeners\User\UserDeleted\LogUserDeleted;
use Modules\Core\Listeners\User\UserRole\LogUserRoleAssigned;
use Modules\Core\Listeners\User\UserRole\LogUserRoleRemoved;
use Modules\Core\Listeners\User\UserRole\NotifyRoleAssigned;
use Modules\Core\Listeners\User\UserRole\NotifyRoleRemoved;
use Modules\Core\Listeners\User\UserUpdated\BroadcastUserUpdated;
use Modules\Core\Listeners\User\UserUpdated\LogUserUpdated;

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
           broadcastRoleCreated::class,
       ],

       RoleUpdated::class => [
           LogRoleUpdated::class,
           broadcastRoleUpdated::class,
       ],

       RoleDeleted::class => [
           LogRoleDeleted::class,
           broadcastRoleDeleted::class,
           NotifyRoleDeleted::class,
       ],

       RoleRestored::class => [
           LogRoleRestored::class,
       ],

       RoleForceDeleted::class => [
           LogRoleForceDeleted::class,
       ],

       RolePermissionAttached::class => [
           LogRolePermissionAttached::class,
           BroadcastRolePermissionChanged::class,
           NotifyRolePermissionChanged::class,
       ],

       RolePermissionDetached::class => [
           LogRolePermissionDetached::class,
           BroadcastRolePermissionChanged::class,
           NotifyRolePermissionChanged::class,
       ],

       RolePermissionsSynced::class => [
           LogRolePermissionsSynced::class,
           BroadcastRolePermissionsSynced::class,
           NotifyRolePermissionsSynced::class,
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
