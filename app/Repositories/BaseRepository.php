<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template TModel of Model
 *
 * @implements BaseRepositoryInterface<TModel>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var TModel
     */
    protected Model $model;

    /**
     * @param TModel $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->get($columns);
    }

    /**
     * @return LengthAwarePaginator<TModel>
     */
    public function paginate(
        int $perPage = 15,
        array $columns = ['*']
    ): LengthAwarePaginator {
        return $this->model
            ->newQuery()
            ->paginate($perPage, $columns);
    }

    /**
     * @return TModel|null
     */
    public function find(
        int $id,
        array $columns = ['*']
    ): ?Model {
        return $this->model
            ->newQuery()
            ->find($id, $columns);
    }

    /**
     * @return TModel
     */
    public function findOrFail(
        int $id,
        array $columns = ['*']
    ): Model {
        return $this->model
            ->newQuery()
            ->findOrFail($id, $columns);
    }

    /**
     * @return TModel|null
     */
    public function findBy(
        string $column,
        mixed $value,
        array $columns = ['*']
    ): ?Model {
        return $this->model
            ->newQuery()
            ->where($column, $value)
            ->first($columns);
    }

    /**
     * @return TModel
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(
        int $id,
        array $data
    ): bool {
        $record = $this->findOrFail($id);

        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);

        return $record->delete();
    }

    public function restore(int $id): bool
    {
        $record = $this->model
            ->newQuery()
            ->withTrashed()
            ->findOrFail($id);

        return $record->restore();
    }

    /**
     * @return TModel
     */
    public function findTrashedOrFail(int $id): Model
    {
        return $this->model
            ->newQuery()
            ->withTrashed()
            ->findOrFail($id);
    }

    public function forceDelete(int $id): bool
    {
        $record = $this->findTrashedOrFail($id);

        return $record->forceDelete();
    }

    /**
     * @return Collection<int, TModel>
     */
    public function with(array $relations): Collection
    {
        return $this->model
            ->newQuery()
            ->with($relations)
            ->get();
    }

    /**
     * @return Collection<int, TModel>
     */
    public function where(
        string $column,
        mixed $value
    ): Collection {
        return $this->model
            ->newQuery()
            ->where($column, $value)
            ->get();
    }

    public function count(): int
    {
        return $this->model
            ->newQuery()
            ->count();
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
