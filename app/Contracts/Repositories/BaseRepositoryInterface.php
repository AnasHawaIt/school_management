<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template TModel of Model
 */
interface BaseRepositoryInterface
{
    /**
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @return LengthAwarePaginator<TModel>
     */
    public function paginate(
        int $perPage = 15,
        array $columns = ['*']
    ): LengthAwarePaginator;

    /**
     * @return TModel|null
     */
    public function find(
        int $id,
        array $columns = ['*']
    ): ?Model;

    /**
     * @return TModel
     */
    public function findOrFail(
        int $id,
        array $columns = ['*']
    ): Model;

    /**
     * @return TModel|null
     */
    public function findBy(
        string $column,
        mixed $value,
        array $columns = ['*']
    ): ?Model;

    /**
     * @return TModel
     */
    public function create(array $data): Model;

    public function update(
        int $id,
        array $data
    ): bool;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    /**
     * @return Collection<int, TModel>
     */
    public function with(array $relations): Collection;

    /**
     * @return Collection<int, TModel>
     */
    public function where(
        string $column,
        mixed $value
    ): Collection;

    public function count(): int;

    public function getModel(): Model;

    /**
     * @return TModel
     */
    public function findTrashedOrFail(int $id): Model;

    public function forceDelete(int $id): bool;
}
