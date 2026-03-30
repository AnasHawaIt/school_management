<?php

namespace Modules\Core\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getActive(): Collection;

    public function getByType(string $type): Collection;

    public function searchByName(string $name): Collection;

    public function getWithRoles(int $id);

    public function getWithFilters(array $filters): LengthAwarePaginator;
}
