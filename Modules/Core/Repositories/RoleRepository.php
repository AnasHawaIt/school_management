<?php

namespace Modules\Core\Repositories;

use App\Repositories\BaseRepository;
use Modules\Core\Contracts\Repositories\RoleRepositoryInterface;
use Modules\Core\Entities\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getWithPermissions(): Collection
    {
        return $this->model->with('permissions')->get();
    }

    public function attachPermissions(int $roleId, array $permissionIds): bool
    {
        $role = $this->findOrFail($roleId);
        //$role->permissions()->attach($permissionIds);
        //this method is repetition any  permissions old and store only the new permissions put method attach store new permissions with old permissions may be repetition
        $role->permissions()->syncWithoutDetaching($permissionIds);
        return true;
    }

    public function detachPermissions(int $roleId, array $permissionIds): bool
    {
        $role = $this->findOrFail($roleId);
        $role->permissions()->detach($permissionIds);
        return true;
    }

    public function syncPermissions(int $roleId, array $permissionIds): bool
    {
        $role = $this->findOrFail($roleId);
        $role->permissions()->sync($permissionIds);
        return true;
    }

    public function getPermissions(int $roleId): Collection
    {
        $role = $this->findOrFail($roleId);
        return $role->permissions;
    }
}
