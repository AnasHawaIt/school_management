<?php

namespace Modules\Core\app\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\app\Entities\Role;

interface RoleServiceInterface
{
    public function getAllRoles(): Collection;

    public function getRole(int $id): ?Role;

    public function createRole(array $data): Role;


    public function updateRole(int $id, array $data): bool;

    public function deleteRole(int $id): bool;

    public function attachPermissions(int $roleId, array $permissions): bool;

    public function detachPermission(int $roleId, int $permissionId): bool;

    public function syncPermissions(int $roleId, array $permissions): bool;

    public function getRolePermissions(int $roleId): Collection;
}
