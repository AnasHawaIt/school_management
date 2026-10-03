<?php


namespace Modules\Activities\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\Activities\Entities\ActivityCategory;
use Modules\Activities\Repositories\Interfaces\ActivityCategoryRepositoryInterface;


class ActivityCategoryRepository implements ActivityCategoryRepositoryInterface
{
    public function __construct(
        protected ActivityCategory $model
    )
    {
    }

    public function findById(int $id): ?ActivityCategory
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): ActivityCategory
    {
        return $this->model->findOrFail($id);
    }

    public function allActive(): Collection
    {
        return $this->model
            ->active()
            ->ordered()
            ->get();
    }

    public function create(array $data): ActivityCategory
    {
        return $this->model->create($data);
    }

    public function update(
        ActivityCategory $category,
        array            $data
    ): ActivityCategory
    {
        $category->update($data);

        return $category->refresh();
    }

    public function delete(ActivityCategory $category): bool
    {
        return (bool)$category->delete();
    }
}
