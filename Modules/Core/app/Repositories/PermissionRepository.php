<?php

namespace Modules\Core\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Core\Entities\Permission;

/**
 * @extends BaseRepository<Permission>
 */
class PermissionRepository
    extends BaseRepository
    implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getByModule(string $module): Collection
    {
        return $this->model
            ->byModule($module)
            ->get();
    }

    public function getAllGrouped(): Collection
    {
        return $this->model
            ->newQuery()
            ->get()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0] ?? 'other';
            });
    }
}
