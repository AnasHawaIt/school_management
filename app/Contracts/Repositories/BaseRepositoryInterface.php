<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Entities\User;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function find(int $id, array $columns = ['*']): ?Role;

    public function findOrFail(int $id, array $columns = ['*']): Role;

    public function findBy(string $column, $value, array $columns = ['*']): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function with(array $relations): Collection;

    public function where(string $column, $value): Collection;

    public function count(): int;
}
