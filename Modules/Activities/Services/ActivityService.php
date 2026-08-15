<?php


namespace Modules\Activities\Services;

use Illuminate\Support\Facades\DB;
use Modules\Activities\Entities\Activity;
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
