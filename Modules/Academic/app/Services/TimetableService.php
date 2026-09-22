<?php

namespace App\Services;

use App\Contracts\Repositories\TimetableRepositoryInterface;
use App\Contracts\Services\TimetableServiceInterface;
use App\Entities\Timetable;
use App\Events\TimetableEvents\TimetableEntryDeleted;
use App\Events\TimetableEvents\TimetableEntryUpdated;
use Illuminate\Support\Facades\Auth;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryCreated;

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

    public function createEntry(array $data): Timetable
    {
        if ($this->timetableRepository->checkConflict($data)) {
            throw new \Exception(
                'Timetable conflict: section or teacher already has a class at this time.'
            );
        }
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
        }  $timetable = $this->timetableRepository->findById($id);

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

    public function deleteEntry(int $id): bool
    {

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
