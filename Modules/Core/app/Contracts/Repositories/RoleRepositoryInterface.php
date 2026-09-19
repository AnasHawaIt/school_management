<?php

namespace Modules\Core\app\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\app\Entities\Permission;
use Modules\Core\app\Entities\Role;

/**
 * @extends BaseRepositoryInterface<Role>
 */
interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, Role>
     */
    public function getWithPermissions(): Collection;

    public function attachPermissions(
        int $roleId,
        array $permissionIds
    ): bool;

    public function detachPermissions(
        int $roleId,
        array $permissionIds
    ): bool;

    public function syncPermissions(
        int $roleId,
        array $permissionIds
    ): bool;

    /**
     * @return Collection<int,Permission>
     */
    public function getPermissions(int $roleId): Collection;
}
