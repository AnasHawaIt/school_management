<?php

namespace Modules\Core\app\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithPermissions(): Collection;

    public function attachPermissions(int $roleId, array $permissionIds): bool;

    public function detachPermissions(int $roleId, array $permissionIds): bool;

    public function syncPermissions(int $roleId, array $permissionIds): bool;

    public function getPermissions(int $roleId): Collection;
}
