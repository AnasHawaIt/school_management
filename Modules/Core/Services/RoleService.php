<?php

namespace Modules\Core\Services;

use Modules\Core\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Core\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Core\Contracts\Services\RoleServiceInterface;
use Modules\Core\Entities\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class RoleService implements RoleServiceInterface
{
    protected $roleRepository;
    protected $activityLogRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        ActivityLogRepositoryInterface $activityLogRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getWithPermissions();
    }

    public function getRole(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    public function createRole(array $data): Role
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->create($data);

            // Attach permissions if provided
            if (isset($data['permissions'])) {
                $this->attachPermissions($role->id, $data['permissions']);
            }

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'create',
                'model_type' => Role::class,
             //   'model_id' => $role->id,
                'new_values' => $role->toArray(),
            ]);

            DB::commit();

            return $role;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRole(int $id, array $data): bool
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->findOrFail($id);
            $oldValues = $role->toArray();

            $updated = $this->roleRepository->update($id, $data);

            // Update permissions if provided
            if (isset($data['permissions'])) {
                $this->syncPermissions($id, $data['permissions']);
            }

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'update',
                'model_type' => Role::class,
          //      'model_id' => $id,
                'old_values' => $oldValues,
                'new_values' => $data,
            ]);

            DB::commit();

            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteRole(int $id): bool
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->findOrFail($id);

            $deleted = $this->roleRepository->delete($id);

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'delete',
                'model_type' => Role::class,
         //       'model_id' => $id,
                'old_values' => $role->toArray(),
            ]);

            DB::commit();

            return $deleted;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function attachPermissions(int $roleId, array $permissions): bool
    {
        return $this->roleRepository->attachPermissions($roleId, $permissions);
    }

    public function detachPermission(int $roleId, int $permissionId): bool
    {
        return $this->roleRepository->detachPermissions($roleId, [$permissionId]);
    }

    public function syncPermissions(int $roleId, array $permissions): bool
    {
        return $this->roleRepository->syncPermissions($roleId, $permissions);
    }

    public function getRolePermissions(int $roleId): Collection
    {
        return $this->roleRepository->getPermissions($roleId);
    }
}
