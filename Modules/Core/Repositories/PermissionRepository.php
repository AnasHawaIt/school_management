<?php

namespace Modules\Core\Repositories;

use App\Repositories\BaseRepository;
use Modules\Core\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Core\Entities\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getByModule(string $module): Collection
    {
        return $this->model->byModule($module)->get();
    }

    public function getAllGrouped(): Collection
    {
        $permissions = $this->model->all();

        return $permissions->groupBy(function ($permission) {
            // Group by module name (e.g., "users.create" -> "users")
            return explode('.', $permission->name)[0] ?? 'other';
        });
    }
}
