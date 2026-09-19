<?php


namespace Modules\Activities\app\Repositories\Eloquent;


use Modules\Activities\app\Entities\ActivitySupervisor;
use Modules\Activities\app\Repositories\Interfaces\ActivitySupervisorRepositoryInterface;

class ActivitySupervisorRepository implements ActivitySupervisorRepositoryInterface
{
    public function __construct(
        protected ActivitySupervisor $model
    )
    {
    }

    public function findById(int $id): ?ActivitySupervisor
    {
        return $this->model
            ->with(['activity', 'teacher.user'])
            ->find($id);
    }

    public function findOrFail(int $id): ActivitySupervisor
    {
        return $this->model
            ->with(['activity', 'teacher.user'])
            ->findOrFail($id);
    }

    public function findForActivity(
        int $activityId,
        int $teacherId
    ): ?ActivitySupervisor
    {
        return $this->model
            ->where('activity_id', $activityId)
            ->where('teacher_id', $teacherId)
            ->first();
    }

    public function primaryForActivity(
        int $activityId
    ): ?ActivitySupervisor
    {
        return $this->model
            ->where('activity_id', $activityId)
            ->where('is_primary', true)
            ->first();
    }

    public function create(array $data): ActivitySupervisor
    {
        return $this->model->create($data);
    }

    public function update(
        ActivitySupervisor $supervisor,
        array              $data
    ): ActivitySupervisor
    {
        $supervisor->update($data);

        return $supervisor->refresh();
    }

    public function delete(ActivitySupervisor $supervisor): bool
    {
        return (bool)$supervisor->delete();
    }

    public function removePrimary(int $activityId): int
    {
        return $this->model
            ->where('activity_id', $activityId)
            ->where('is_primary', true)
            ->update([
                'is_primary' => false,
            ]);
    }
}
