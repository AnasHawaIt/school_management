<?php

namespace Modules\Academic\Services;

use Illuminate\Support\Facades\Auth;

use Modules\Academic\Contracts\Services\TimetableServiceInterface;
use Modules\Academic\Contracts\Repositories\TimetableRepositoryInterface;
use Modules\Academic\Entities\Timetable;
use Modules\Academic\Events\TimetableEvents\TimetableEntryCreated;
use Modules\Academic\Events\TimetableEvents\TimetableEntryDeleted;
use Modules\Academic\Events\TimetableEvents\TimetableEntryUpdated;


class TimetableService implements TimetableServiceInterface
{
    public function __construct(
        protected TimetableRepositoryInterface $timetableRepository,
    ) {}

    public function getAllTimetables(array $filters = [])
    {
        return $this->timetableRepository->getAll($filters);
    }

    public function getTimetable(int $id)
    {
        return $this->timetableRepository->findById($id);
    }

    /**
     * Create timetable entry.
     */
    public function createEntry(array $data): Timetable
    {
        /*
        |--------------------------------------------------------------------------
        | Check conflict
        |--------------------------------------------------------------------------
        */

        if ($this->timetableRepository->checkConflict($data)) {
            throw new \Exception(
                'Timetable conflict: section or teacher already has a class at this time.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $timetable = $this->timetableRepository->create($data);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new TimetableEntryCreated(
            timetable: $timetable,
            userId: Auth::id(),
        ));

        return $timetable;
    }

    /**
     * Update timetable entry.
     */
    public function updateEntry(
        int $id,
        array $data
    ): Timetable {
        /*
        |--------------------------------------------------------------------------
        | Check conflict
        |--------------------------------------------------------------------------
        */

        if (
            $this->timetableRepository->checkConflict(
                $data,
                $id
            )
        ) {
            throw new \Exception(
                'Timetable conflict: section or teacher already has a class at this time.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get current model
        |--------------------------------------------------------------------------
        */

        $timetable = $this->timetableRepository->findById($id);

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $updated = $this->timetableRepository->update(
            $id,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Changes
        |--------------------------------------------------------------------------
        */

        $changes = $updated->getChanges();

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new TimetableEntryUpdated(
            timetable: $updated,
            changes: $changes,
            userId: Auth::id(),
        ));

        return $updated;
    }

    /**
     * Delete timetable entry.
     */
    public function deleteEntry(int $id): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Get model before deleting
        |--------------------------------------------------------------------------
        */

        $timetable = $this->timetableRepository->findById($id);

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $result = $this->timetableRepository->delete($id);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($result) {
            event(new TimetableEntryDeleted(
                timetable: $timetable,
                userId: Auth::id(),
            ));
        }

        return $result;
    }

    public function getSectionTimetable(
        int $sectionId,
        int $semesterId
    ) {
        return $this->timetableRepository
            ->getBySectionAndSemester(
                $sectionId,
                $semesterId
            );
    }

    public function getTeacherTimetable(
        int $teacherId,
        int $semesterId
    ) {
        return $this->timetableRepository
            ->getByTeacherAndSemester(
                $teacherId,
                $semesterId
            );
    }
}
