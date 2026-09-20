<?php


namespace Modules\Activities\app\Repositories\Interfaces;

use Modules\Activities\app\Entities\ActivityParticipant;

interface ActivityParticipantRepositoryInterface
{
    public function findById(int $id): ?ActivityParticipant;

    public function findOrFail(int $id): ActivityParticipant;

    public function findForActivity(
        int    $activityId,
        string $participantType,
        int    $participantId
    ): ?ActivityParticipant;

    public function countActiveParticipants(int $activityId): int;

    public function create(array $data): ActivityParticipant;

    public function update(
        ActivityParticipant $participant,
        array               $data
    ): ActivityParticipant;

    public function delete(ActivityParticipant $participant): bool;
}
