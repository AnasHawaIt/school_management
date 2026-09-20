<?php


namespace Modules\Activities\app\Repositories\Interfaces;

use Modules\Activities\app\Entities\ActivitySupervisor;

interface ActivitySupervisorRepositoryInterface
{
    public function findById(int $id): ?ActivitySupervisor;

    public function findOrFail(int $id): ActivitySupervisor;

    public function findForActivity(
        int $activityId,
        int $teacherId
    ): ?ActivitySupervisor;

    public function primaryForActivity(
        int $activityId
    ): ?ActivitySupervisor;

    public function create(array $data): ActivitySupervisor;

    public function update(
        ActivitySupervisor $supervisor,
        array              $data
    ): ActivitySupervisor;

    public function delete(ActivitySupervisor $supervisor): bool;

    public function removePrimary(
        int $activityId
    ): int;
}
