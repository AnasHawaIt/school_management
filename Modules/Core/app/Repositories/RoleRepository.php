<?php

namespace Modules\Core\app\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\app\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Core\app\Entities\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getWithPermissions(): Collection
    {
        return $this->model
            ->with('permissions')
            ->get();
    }

    public function attachPermissions(
        int $roleId,
        array $permissionIds
    ): bool {
        $role = $this->findOrFail($roleId);

        $role->permissions()->syncWithoutDetaching(
            $permissionIds
        );

        return true;
    }

    public function detachPermissions(
        int $roleId,
        array $permissionIds
    ): bool {
        $role = $this->findOrFail($roleId);

        $role->permissions()->detach(
            $permissionIds
        );

        return true;
    }

    public function syncPermissions(
        int $roleId,
        array $permissionIds
    ): bool {
        $role = $this->findOrFail($roleId);

        $role->permissions()->sync(
            $permissionIds
        );

        return true;
    }

    public function getPermissions(
        int $roleId
    ): Collection {
        $role = $this->findOrFail($roleId);

        return $role->permissions;
    }
}
