<?php

namespace Modules\Core\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\app\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Core\app\Contracts\Services\PermissionServiceInterface;
use Modules\Core\app\Entities\Permission;
use Modules\Core\app\Events\Permission\PermissionCreated;
use Modules\Core\app\Events\Permission\PermissionDeleted;
use Modules\Core\app\Events\Permission\PermissionForceDeleted;
use Modules\Core\app\Events\Permission\PermissionRestored;
use Modules\Core\app\Events\Permission\PermissionUpdated;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->all();
    }

    public function getPermissionsByModule(string $module): Collection
    {
        return $this->permissionRepository->getByModule($module);
    }

    public function getAllGrouped(): Collection
    {
        return $this->permissionRepository->getAllGrouped();
    }

    public function getPermission(int $id): ?Permission
    {
        return $this->permissionRepository->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function createPermission(
        array $data,
        ?int $userId = null
    ): Permission {
        return DB::transaction(function () use ($data, $userId) {

            $permission = $this->permissionRepository->create($data);

            event(new PermissionCreated(
                permission: $permission,
                userId: $userId,
            ));

            return $permission;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updatePermission(
        int $id,
        array $data,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use (
            $id,
            $data,
            $userId
        ) {
            $permission = $this->permissionRepository->findOrFail($id);

            $oldValues = $permission->toArray();

            $updated = $this->permissionRepository->update(
                $id,
                $data
            );

            if ($updated) {
                $permission->refresh();

                event(new PermissionUpdated(
                    permission: $permission,
                    userId: $userId,
                    oldValues: $oldValues,
                    newValues: $permission->toArray(),
                ));
            }

            return $updated;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Soft Delete
    |--------------------------------------------------------------------------
    */

    public function deletePermission(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $permission = $this->permissionRepository->findOrFail($id);

            $deleted = $this->permissionRepository->delete($id);

            if ($deleted) {
                event(new PermissionDeleted(
                    permission: $permission,
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

    public function restorePermission(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $permission = $this->permissionRepository
                ->findTrashedOrFail($id);

            $restored = $this->permissionRepository
                ->restore($id);

            if ($restored) {
                $permission->refresh();

                event(new PermissionRestored(
                    permission: $permission,
                    userId: $userId,
                ));
            }

            return $restored;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    public function forceDeletePermission(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $permission = $this->permissionRepository
                ->findTrashedOrFail($id);

            $forceDeleted = $this->permissionRepository
                ->forceDelete($id);

            if ($forceDeleted) {
                event(new PermissionForceDeleted(
                    permission: $permission,
                    userId: $userId,
                ));
            }

            return $forceDeleted;
        });
    }
}
