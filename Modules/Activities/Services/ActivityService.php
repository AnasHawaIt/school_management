<?php


namespace Modules\Activities\Services;

use Illuminate\Support\Facades\DB;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivityParticipant;
use Modules\Activities\Repositories\Interfaces\ActivityParticipantRepositoryInterface;
use Modules\Activities\Repositories\Interfaces\ActivityRepositoryInterface;

class ActivityService
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected ActivityParticipantRepositoryInterface $participantRepository
    ) {
    }

    public function create(array $data): Activity
    {
        $data['status'] ??= 'draft';

        return DB::transaction(function () use ($data) {
            return $this->activityRepository->create($data);
        });
    }

    public function registerParticipant(
        Activity $activity,
        string $participantType,
        int $participantId,
        ?string $role = null
    ): ActivityParticipant {

        if ($activity->status !== 'published') {
            throw new \DomainException(
                'Participants can only register for published activities.'
            );
        }

        if (!$activity->registration_required) {
            throw new \DomainException(
                'Registration is not required for this activity.'
            );
        }

        if (
            $activity->registration_deadline &&
            now()->greaterThan($activity->registration_deadline)
        ) {
            throw new \DomainException(
                'Registration deadline has passed.'
            );
        }

        // 4. التحقق من التسجيل السابق
        $existing = $this->participantRepository->findForActivity(
            $activity->id,
            $participantType,
            $participantId
        );

        if ($existing && $existing->status !== 'cancelled') {
            throw new \DomainException(
                'Participant is already registered for this activity.'
            );
        }

        // 5. التحقق من السعة
        if ($activity->capacity !== null) {
            $count = $this->participantRepository
                ->countActiveParticipants($activity->id);

            if ($count >= $activity->capacity) {
                throw new \DomainException(
                    'Activity capacity has been reached.'
                );
            }
        }

        return DB::transaction(function () use (
            $activity,
            $participantType,
            $participantId,
            $role,
            $existing
        ) {
            if ($existing) {
                return $this->participantRepository->update(
                    $existing,
                    [
                        'status' => 'registered',
                        'role' => $role,
                        'registered_at' => now(),
                        'confirmed_at' => null,
                        'attended_at' => null,
                    ]
                );
            }

            return $this->participantRepository->create([
                'activity_id' => $activity->id,
                'participant_type' => $participantType,
                'participant_id' => $participantId,
                'role' => $role,
                'status' => 'registered',
                'registered_at' => now(),
            ]);
        });
    }

    public function update(
        Activity $activity,
        array $data
    ): Activity {
        unset($data['status']);

        return DB::transaction(function () use (
            $activity,
            $data
        ) {
            return $this->activityRepository->update(
                $activity,
                $data
            );
        });
    }

    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== 'draft') {
            throw new \DomainException(
                'Only draft activities can be published.'
            );
        }

        if ($activity->start_at->isPast()) {
            throw new \DomainException(
                'An activity with a past start date cannot be published.'
            );
        }

        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'published',
                ]
            );
        });
    }

    public function cancel(Activity $activity): Activity
    {
        if ($activity->status === 'completed') {
            throw new \DomainException(
                'Completed activities cannot be cancelled.'
            );
        }

        if ($activity->status === 'cancelled') {
            throw new \DomainException(
                'Activity is already cancelled.'
            );
        }

        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'cancelled',
                ]
            );
        });
    }

    public function start(Activity $activity): Activity
    {
        if ($activity->status !== 'published') {
            throw new \DomainException(
                'Only published activities can be started.'
            );
        }

        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'ongoing',
                ]
            );
        });
    }

    public function markAttendance(
        ActivityParticipant $participant
    ): ActivityParticipant {
        if (!in_array(
            $participant->status,
            ['registered', 'confirmed'],
            true
        )) {
            throw new \DomainException(
                'Participant cannot be marked as attended.'
            );
        }

        return DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'attended',
                    'attended_at' => now(),
                ]
            );
        });
    }

    public function markAbsent(
        ActivityParticipant $participant
    ): ActivityParticipant {
        if (!in_array(
            $participant->status,
            ['registered', 'confirmed'],
            true
        )) {
            throw new \DomainException(
                'Participant cannot be marked as absent.'
            );
        }

        return DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'absent',
                ]
            );
        });
    }

    public function cancelParticipant(
        ActivityParticipant $participant
    ): ActivityParticipant {
        if (in_array(
            $participant->status,
            ['attended', 'absent'],
            true
        )) {
            throw new \DomainException(
                'Attendance has already been recorded.'
            );
        }

        return DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'cancelled',
                ]
            );
        });
    }


    public function confirmParticipant(
        ActivityParticipant $participant
    ): ActivityParticipant {
        if ($participant->status !== 'registered') {
            throw new \DomainException(
                'Only registered participants can be confirmed.'
            );
        }

        return DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]
            );
        });
    }

    public function complete(Activity $activity): Activity
    {
        if ($activity->status !== 'ongoing') {
            throw new \DomainException(
                'Only ongoing activities can be completed.'
            );
        }

        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'completed',
                ]
            );
        });
    }

    public function delete(Activity $activity): bool
    {
        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->delete($activity);
        });
    }
}
