<?php


namespace Modules\Activities\Services;

use Illuminate\Support\Facades\DB;
use Modules\Academic\Entities\Teacher;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivityParticipant;
use Modules\Activities\Entities\ActivitySupervisor;
use Modules\Activities\Events\ActivityCancelled;
use Modules\Activities\Events\ActivityCompleted;
use Modules\Activities\Events\ActivityCreated;
use Modules\Activities\Events\ActivityParticipantAbsent;
use Modules\Activities\Events\ActivityParticipantAttended;
use Modules\Activities\Events\ActivityParticipantCancelled;
use Modules\Activities\Events\ActivityParticipantConfirmed;
use Modules\Activities\Events\ActivityParticipantRegistered;
use Modules\Activities\Events\ActivityPrimarySupervisorChanged;
use Modules\Activities\Events\ActivityPublished;
use Modules\Activities\Events\ActivityStarted;
use Modules\Activities\Events\ActivitySupervisorAdded;
use Modules\Activities\Events\ActivitySupervisorRemoved;
use Modules\Activities\Events\ActivityUpdated;
use Modules\Activities\Repositories\Interfaces\ActivityParticipantRepositoryInterface;
use Modules\Activities\Repositories\Interfaces\ActivityRepositoryInterface;
use Modules\Activities\Repositories\Interfaces\ActivitySupervisorRepositoryInterface;

class ActivityService
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected ActivityParticipantRepositoryInterface $participantRepository,
        protected ActivitySupervisorRepositoryInterface $supervisorRepository
    ) {
    }

    public function create(array $data): Activity
    {
        $data['status'] ??= 'draft';

        $activity = DB::transaction(function () use ($data) {
            return $this->activityRepository->create($data);
        });

        ActivityCreated::dispatch($activity);

        return $activity;
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

        if ($activity->capacity !== null) {
            $count = $this->participantRepository
                ->countActiveParticipants($activity->id);

            if ($count >= $activity->capacity) {
                throw new \DomainException(
                    'Activity capacity has been reached.'
                );
            }
        }

        $participant = DB::transaction(function () use (
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

        ActivityParticipantRegistered::dispatch($participant);

        return $participant;

    }

    public function update(
        Activity $activity,
        array $data
    ): Activity {
        $activity = DB::transaction(function () use (
            $activity,
            $data
        ) {
            return $this->activityRepository->update(
                $activity,
                $data
            );
        });

        ActivityUpdated::dispatch($activity);

        return $activity;
    }

    public function cancel(
        Activity $activity
    ): Activity {
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

        $activity = DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'cancelled',
                ]
            );
        });

        ActivityCancelled::dispatch($activity);

        return $activity;
    }

    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== 'draft') {
            throw new \DomainException(
                'Only draft activities can be published.'
            );
        }

        if (
            $activity->start_at &&
            $activity->start_at->isPast()
        ) {
            throw new \DomainException(
                'An activity with a past start date cannot be published.'
            );
        }

        $activity = DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'published',
                ]
            );
        });

        ActivityPublished::dispatch($activity);

        return $activity;
    }

    public function start(
        Activity $activity
    ): Activity {
        if ($activity->status !== 'published') {
            throw new \DomainException(
                'Only published activities can be started.'
            );
        }

        $activity = DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'ongoing',
                    'started_at' => now(),
                ]
            );
        });

        ActivityStarted::dispatch($activity);

        return $activity;
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

        $participant = DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'attended',
                    'attended_at' => now(),
                ]
            );
        });

        ActivityParticipantAttended::dispatch($participant);

        return $participant;
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

        $participant = DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'absent',
                ]
            );
        });

        ActivityParticipantAbsent::dispatch($participant);

        return $participant;
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

        $participant = DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'cancelled',
                ]
            );
        });

        ActivityParticipantCancelled::dispatch($participant);

        return $participant;
    }

    public function confirmParticipant(
        ActivityParticipant $participant
    ): ActivityParticipant {
        if ($participant->status !== 'registered') {
            throw new \DomainException(
                'Only registered participants can be confirmed.'
            );
        }

        $participant = DB::transaction(function () use ($participant) {
            return $this->participantRepository->update(
                $participant,
                [
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]
            );
        });

        ActivityParticipantConfirmed::dispatch($participant);

        return $participant;
    }

    public function addSupervisor(
        Activity $activity,
        int $teacherId,
        ?string $role = null,
        bool $isPrimary = false,
        ?string $notes = null
    ): ActivitySupervisor {
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            throw new \DomainException(
                'Teacher not found.'
            );
        }

        if ($teacher->status !== 'active') {
            throw new \DomainException(
                'Only active teachers can supervise activities.'
            );
        }

        $existing = $this->supervisorRepository
            ->findForActivity(
                $activity->id,
                $teacherId
            );

        if ($existing && !$existing->trashed()) {
            throw new \DomainException(
                'Teacher is already a supervisor for this activity.'
            );
        }

        $supervisor = DB::transaction(function () use (
            $activity,
            $teacherId,
            $role,
            $isPrimary,
            $notes,
            $existing
        ) {
            if ($isPrimary) {
                $this->supervisorRepository
                    ->removePrimary($activity->id);
            }

            if ($existing) {
                $existing->restore();

                return $this->supervisorRepository->update(
                    $existing,
                    [
                        'role' => $role,
                        'is_primary' => $isPrimary,
                        'notes' => $notes,
                    ]
                );
            }

            return $this->supervisorRepository->create([
                'activity_id' => $activity->id,
                'teacher_id' => $teacherId,
                'role' => $role,
                'is_primary' => $isPrimary,
                'notes' => $notes,
            ]);
        });

        ActivitySupervisorAdded::dispatch($supervisor);

        return $supervisor;
    }

    public function setPrimarySupervisor(
        ActivitySupervisor $supervisor
    ): ActivitySupervisor {
        $supervisor = DB::transaction(function () use ($supervisor) {

            $this->supervisorRepository
                ->removePrimary($supervisor->activity_id);

            return $this->supervisorRepository->update(
                $supervisor,
                [
                    'is_primary' => true,
                ]
            );
        });

        ActivityPrimarySupervisorChanged::dispatch($supervisor);

        return $supervisor;
    }

    public function removeSupervisor(
        ActivitySupervisor $supervisor
    ): bool {
        if ($supervisor->is_primary) {
            throw new \DomainException(
                'The primary supervisor cannot be removed. Assign another primary supervisor first.'
            );
        }

        $supervisor->load('activity');

        $deleted = DB::transaction(function () use ($supervisor) {
            return $this->supervisorRepository->delete(
                $supervisor
            );
        });

        if ($deleted) {
            ActivitySupervisorRemoved::dispatch($supervisor);
        }

        return $deleted;
    }

    public function complete(
        Activity $activity
    ): Activity {
        if ($activity->status !== 'ongoing') {
            throw new \DomainException(
                'Only ongoing activities can be completed.'
            );
        }

        $activity = DB::transaction(function () use ($activity) {
            return $this->activityRepository->update(
                $activity,
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );
        });

        ActivityCompleted::dispatch($activity);

        return $activity;
    }

    public function delete(Activity $activity): bool
    {
        return DB::transaction(function () use ($activity) {
            return $this->activityRepository->delete($activity);
        });
    }
}
