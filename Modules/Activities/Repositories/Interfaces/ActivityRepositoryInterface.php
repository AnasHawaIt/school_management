<?php


namespace Modules\Activities\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Activities\Entities\Activity;

interface ActivityRepositoryInterface
{
    public function findById(
        int   $id,
        array $with = []
    ): ?Activity;

    public function findOrFail(
        int   $id,
        array $with = []
    ): Activity;

    public function paginate(
        int   $perPage = 15,
        array $filters = [],
        array $with = []
    ): LengthAwarePaginator;

    public function create(array $data): Activity;

    public function update(
        Activity $activity,
        array    $data
    ): Activity;

    public function delete(Activity $activity): bool;
}
