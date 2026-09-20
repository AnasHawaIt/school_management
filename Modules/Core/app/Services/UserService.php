<?php

namespace Modules\Core\app\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\app\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\app\Contracts\Services\UserServiceInterface;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Entities\User;
use Modules\Core\app\Events\User\UserCreated;
use Modules\Core\app\Events\User\UserDeleted;
use Modules\Core\app\Events\User\UserPasswordChanged;
use Modules\Core\app\Events\User\UserRestored;
use Modules\Core\app\Events\User\UserRoleAssigned;
use Modules\Core\app\Events\User\UserRoleRemoved;
use Modules\Core\app\Events\User\UserUpdated;

class UserService implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->getWithFilters($filters);
    }

    public function getUser(int $id): ?User
    {
        return $this->userRepository->getWithRoles($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function createUser(
        array $data,
        ?int $userId = null
    ): User {
        return DB::transaction(function () use ($data, $userId) {

            /*
            |--------------------------------------------------------------------------
            | Extract role because it is not a users table column
            |--------------------------------------------------------------------------
            */

            $role = $data['role'] ?? null;

            unset($data['role']);

            /*
            |--------------------------------------------------------------------------
            | Hash password
            |--------------------------------------------------------------------------
            */

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            /*
            |--------------------------------------------------------------------------
            | Store avatar
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['avatar']) &&
                $data['avatar'] instanceof UploadedFile
            ) {
                $data['avatar'] = $data['avatar']->store(
                    'avatars',
                    'public'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create user
            |--------------------------------------------------------------------------
            */

            $user = $this->userRepository->create($data);

            /*
            |--------------------------------------------------------------------------
            | Assign role
            |--------------------------------------------------------------------------
            */

            if ($role !== null) {
                $user->assignRole($role);
            }

            /*
            |--------------------------------------------------------------------------
            | Event
            |--------------------------------------------------------------------------
            */

            event(new UserCreated(
                user: $user->fresh('roles'),
                userId: $userId,
            ));

            return $user->fresh('roles');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateUser(
        int $id,
        array $data,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use (
            $id,
            $data,
            $userId
        ) {
            $user = $this->userRepository->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Keep original values for event
            |--------------------------------------------------------------------------
            */

            $oldValues = $user->toArray();

            /*
            |--------------------------------------------------------------------------
            | Extract role
            |--------------------------------------------------------------------------
            */

            $role = $data['role'] ?? null;

            unset($data['role']);

            /*
            |--------------------------------------------------------------------------
            | Detect password change
            |--------------------------------------------------------------------------
            */

            $passwordChanged = array_key_exists(
                'password',
                $data
            );

            /*
            |--------------------------------------------------------------------------
            | Hash password
            |--------------------------------------------------------------------------
            */

            if ($passwordChanged) {
                $data['password'] = Hash::make(
                    $data['password']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Store avatar
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['avatar']) &&
                $data['avatar'] instanceof UploadedFile
            ) {
                $data['avatar'] = $data['avatar']->store(
                    'avatars',
                    'public'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Update user
            |--------------------------------------------------------------------------
            */

            $updated = $this->userRepository->update(
                $id,
                $data
            );

            if (!$updated) {
                return false;
            }

            $user->refresh();

            /*
            |--------------------------------------------------------------------------
            | Role change
            |--------------------------------------------------------------------------
            */

            if ($role !== null) {
                $oldRole = $user->roles()->first();

                $oldRoleName = $oldRole?->name;

                /*
                | Remove existing roles
                */

                $user->roles()->sync([]);

                /*
                | Assign new role
                */

                $user->assignRole($role);

                $user->refresh();

                /*
                |--------------------------------------------------------------------------
                | Role Assigned Event
                |--------------------------------------------------------------------------
                */

                event(new UserRoleAssigned(
                    user: $user,
                    role: $role,
                    userId: $userId,
                ));

                /*
                |--------------------------------------------------------------------------
                | If an old role existed and changed,
                | emit RoleRemoved as well.
                |--------------------------------------------------------------------------
                */

                if (
                    $oldRoleName !== null &&
                    $oldRoleName !== $role
                ) {
                    event(new UserRoleRemoved(
                        user: $user,
                        role: $oldRoleName,
                        userId: $userId,
                    ));
                }
            }

            /*
            |--------------------------------------------------------------------------
            | User Updated Event
            |--------------------------------------------------------------------------
            */

            event(new UserUpdated(
                user: $user,
                userId: $userId,
            ));

            /*
            |--------------------------------------------------------------------------
            | Password Changed Event
            |--------------------------------------------------------------------------
            */

            if ($passwordChanged) {
                event(new UserPasswordChanged(
                    user: $user,
                    userId: $userId,
                ));
            }

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteUser(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $user = $this->userRepository->findOrFail($id);

            $deleted = $this->userRepository->delete($id);

            if ($deleted) {
                event(new UserDeleted(
                    user: $user,
                    userId: $userId,
                ));
            }

            return $deleted;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restoreUser(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $user = $this->userRepository->restore($id);

            if ($user) {
                $restoredUser = $this->userRepository
                    ->findTrashedOrFail($id);

                event(new UserRestored(
                    user: $restoredUser,
                    userId: $userId,
                ));
            }

            return $user;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Assign Role
    |--------------------------------------------------------------------------
    */

    public function assignRole(
        int $userId,
        string $role,
        ?int $causerId = null
    ): bool {
        return DB::transaction(function () use (
            $userId,
            $role,
            $causerId
        ) {
            $user = $this->userRepository->findOrFail($userId);

            $user->assignRole($role);

            $user->refresh();

            event(new UserRoleAssigned(
                user: $user,
                role: Role::query()->findOrFail($role),
                userId: $causerId,
            ));

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Role
    |--------------------------------------------------------------------------
    */

    public function removeRole(
        int $userId,
        string $role,
        ?int $causerId = null
    ): bool {
        return DB::transaction(function () use (
            $userId,
            $role,
            $causerId
        ) {
            $user = $this->userRepository->findOrFail($userId);

            $user->removeRole($role);

            $user->refresh();

            event(new UserRoleRemoved(
                user: $user,
                role: Role::query()->findOrFail($role),
                userId: $causerId,
            ));

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    public function getUserPermissions(int $userId): array
    {
        $user = $this->userRepository->findOrFail($userId);

        return $user->getPermissions()
            ->pluck('name')
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

    public function changePassword(
        int $userId,
        string $newPassword,
        ?int $causerId = null
    ): bool {
        return DB::transaction(function () use (
            $userId,
            $newPassword,
            $causerId
        ) {
            $user = $this->userRepository->findOrFail($userId);

            $updated = $this->userRepository->update(
                $userId,
                [
                    'password' => Hash::make($newPassword),
                ]
            );

            if ($updated) {
                $user->refresh();

                event(new UserPasswordChanged(
                    user: $user,
                    userId: $causerId,
                ));
            }

            return $updated;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FCM Token
    |--------------------------------------------------------------------------
    */

    public function updateFcmToken(
        int $userId,
        string $token
    ): void {
        User::where('id', $userId)
            ->update([
                'fcm_token' => $token,
            ]);
    }
}
