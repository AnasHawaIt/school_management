<?php

namespace Modules\Core\app\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\app\Entities\Permission;

interface PermissionServiceInterface
{
    public function getAllPermissions(): Collection;

    public function getPermissionsByModule(string $module): Collection;

    public function getAllGrouped(): Collection;

    public function getPermission(int $id): ?Permission;

    public function createPermission(
        array $data,
        ?int $userId = null
    ): Permission;

    public function updatePermission(
        int $id,
        array $data,
        ?int $userId = null
    ): bool;

    public function deletePermission(
        int $id,
        ?int $userId = null
    ): bool;

    public function restorePermission(
        int $id,
        ?int $userId = null
    ): bool;

    public function forceDeletePermission(
        int $id,
        ?int $userId = null
    ): bool;
}
