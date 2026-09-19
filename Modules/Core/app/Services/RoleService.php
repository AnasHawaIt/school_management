<?php

namespace Modules\Core\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\app\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Core\app\Contracts\Services\RoleServiceInterface;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Events\Role\RoleCreated;
use Modules\Core\app\Events\Role\RoleDeleted;
use Modules\Core\app\Events\Role\RolePermissionAttached;
use Modules\Core\app\Events\Role\RolePermissionDetached;
use Modules\Core\app\Events\Role\RolePermissionsSynced;
use Modules\Core\app\Events\Role\RoleUpdated;

class RoleService implements RoleServiceInterface
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {
    }

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getWithPermissions();
    }

    public function getRole(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Create a new role.
     */
    public function createRole(
        array $data,
        ?int $userId = null
    ): Role {
        return DB::transaction(function () use ($data, $userId) {

            $permissionIds = $data['permissions'] ?? [];

            // Do not pass permissions to role creation.
            unset($data['permissions']);

            $role = $this->roleRepository->create($data);

            event(new RoleCreated(
                role: $role,
                userId: $userId,
            ));

            if (!empty($permissionIds)) {
                $this->attachPermissions(
                    roleId: $role->id,
                    permissions: $permissionIds,
                    userId: $userId
                );
            }

            return $role->fresh('permissions');
        });
    }

    /**
     * Update an existing role.
     */
    public function updateRole(
        int $id,
        array $data,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $data, $userId) {

            $role = $this->roleRepository->findOrFail($id);

            $oldValues = $role->toArray();

            /*
             * Keep permissions separate from role attributes.
             */
            $permissionIds = $data['permissions'] ?? null;
            unset($data['permissions']);

            $updated = $this->roleRepository->update(
                $id,
                $data
            );

            if ($permissionIds !== null) {
                $this->syncPermissions(
                    roleId: $id,
                    permissions: $permissionIds,
                    userId: $userId
                );
            }

            $role->refresh();

            event(new RoleUpdated(
                role: $role,
                userId: $userId,
                oldValues: $oldValues,
                newValues: $data,
            ));

            return $updated;
        });
    }

    /**
     * Delete a role.
     */
    public function deleteRole(
        int $id,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use ($id, $userId) {

            $role = $this->roleRepository->findOrFail($id);

            $deleted = $this->roleRepository->delete($id);

            if ($deleted) {
                event(new RoleDeleted(
                    role: $role,
                    userId: $userId,
                ));
            }

            return $deleted;
        });
    }

    /**
     * Attach permissions without removing existing permissions.
     */
    public function attachPermissions(
        int $roleId,
        array $permissions,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use (
            $roleId,
            $permissions,
            $userId
        ) {
            $role = $this->roleRepository->findOrFail($roleId);

            /*
             * Get current permissions first so the event
             * contains only actually new permissions.
             */
            $existingPermissionIds = $role->permissions()
                ->pluck('permissions.id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $permissionIds = array_values(
                array_unique(
                    array_map('intval', $permissions)
                )
            );

            $attachedPermissionIds = array_values(
                array_diff(
                    $permissionIds,
                    $existingPermissionIds
                )
            );

            $result = $this->roleRepository->attachPermissions(
                $roleId,
                $permissionIds
            );

            if ($result && !empty($attachedPermissionIds)) {
                event(new RolePermissionAttached(
                    role: $role->fresh('permissions'),
                    permissionIds: $attachedPermissionIds,
                    userId: $userId,
                ));
            }

            return $result;
        });
    }

    /**
     * Detach specific permissions from a role.
     */
    public function detachPermission(
        int $roleId,
        int $permissionId,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use (
            $roleId,
            $permissionId,
            $userId
        ) {
            $role = $this->roleRepository->findOrFail($roleId);

            $exists = $role->permissions()
                ->where('permissions.id', $permissionId)
                ->exists();

            $result = $this->roleRepository->detachPermissions(
                $roleId,
                [$permissionId]
            );

            if ($result && $exists) {
                event(new RolePermissionDetached(
                    role: $role->fresh('permissions'),
                    permissionIds: [$permissionId],
                    userId: $userId,
                ));
            }

            return $result;
        });
    }

    /**
     * Synchronize all permissions for a role.
     */
    public function syncPermissions(
        int $roleId,
        array $permissions,
        ?int $userId = null
    ): bool {
        return DB::transaction(function () use (
            $roleId,
            $permissions,
            $userId
        ) {
            $role = $this->roleRepository->findOrFail($roleId);

            $oldPermissionIds = $role->permissions()
                ->pluck('permissions.id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->toArray();

            $newPermissionIds = array_values(
                array_unique(
                    array_map('intval', $permissions)
                )
            );

            sort($newPermissionIds);

            $result = $this->roleRepository->syncPermissions(
                $roleId,
                $newPermissionIds
            );

            if (
                $result &&
                $oldPermissionIds !== $newPermissionIds
            ) {
                event(new RolePermissionsSynced(
                    role: $role->fresh('permissions'),
                    oldPermissionIds: $oldPermissionIds,
                    newPermissionIds: $newPermissionIds,
                    userId: $userId,
                ));
            }

            return $result;
        });
    }

    public function getRolePermissions(int $roleId): Collection
    {
        return $this->roleRepository->getPermissions($roleId);
    }
}
