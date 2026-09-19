<?php

namespace Modules\Core\app\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\app\Entities\User;

/**
 * @extends BaseRepositoryInterface<User>
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection<int, User>
     */
    public function getActive(): Collection;

    /**
     * @return Collection<int, User>
     */
    public function getByType(string $type): Collection;

    /**
     * @return Collection<int, User>
     */
    public function searchByName(string $name): Collection;

    public function getWithRoles(int $id): ?User;

    /**
     * @return LengthAwarePaginator<User>
     */
    public function getWithFilters(array $filters): LengthAwarePaginator;
}
