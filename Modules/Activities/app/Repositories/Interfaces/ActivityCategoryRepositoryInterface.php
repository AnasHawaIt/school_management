<?php


namespace Modules\Activities\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\Activities\Entities\ActivityCategory;

interface ActivityCategoryRepositoryInterface
{
    public function findById(int $id): ?ActivityCategory;

    public function findOrFail(int $id): ActivityCategory;

    public function allActive(): Collection;

    public function create(array $data): ActivityCategory;

    public function update(
        ActivityCategory $category,
        array            $data
    ): ActivityCategory;

    public function delete(ActivityCategory $category): bool;
}
