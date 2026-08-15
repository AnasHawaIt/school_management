<?php

namespace Modules\Activities\Repositories\Eloquent;

use Modules\Activities\Entities\ActivityParticipant;
use Modules\Activities\Repositories\Interfaces\ActivityParticipantRepositoryInterface;

class ActivityParticipantRepository implements ActivityParticipantRepositoryInterface
{
    public function __construct(
        protected ActivityParticipant $model
    ) {
    }

    public function findById(int $id): ?ActivityParticipant
    {
        return $this->model
            ->with(['activity', 'participant'])
            ->find($id);
    }

    public function findOrFail(int $id): ActivityParticipant
    {
        return $this->model
            ->with(['activity', 'participant'])
            ->findOrFail($id);
    }

    public function findForActivity(
        int $activityId,
        string $participantType,
        int $participantId
    ): ?ActivityParticipant {
        return $this->model
            ->where('activity_id', $activityId)
            ->where('participant_type', $participantType)
            ->where('participant_id', $participantId)
            ->first();
    }

    public function countActiveParticipants(int $activityId): int
    {
        return $this->model
            ->where('activity_id', $activityId)
            ->active()
            ->count();
    }

    public function create(array $data): ActivityParticipant
    {
        return $this->model->create($data);
    }

    public function update(
        ActivityParticipant $participant,
        array $data
    ): ActivityParticipant {
        $participant->update($data);

        return $participant->refresh();
    }

    public function delete(ActivityParticipant $participant): bool
    {
        return (bool) $participant->delete();
    }
}
