<?php

namespace Modules\Core\app\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function getByModule(string $module): Collection;

    public function getAllGrouped(): Collection;
}
