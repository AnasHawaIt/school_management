<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Services\TimetableServiceInterface;
use Modules\Academic\Contracts\Repositories\TimetableRepositoryInterface;

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

    public function createEntry(array $data): object
    {
        if ($this->timetableRepository->checkConflict($data)) {
            throw new \Exception('Timetable conflict: section or teacher already has a class at this time.');
        }
        return $this->timetableRepository->create($data);
    }

    public function updateEntry(int $id, array $data): object
    {
        if ($this->timetableRepository->checkConflict($data, $id)) {
            throw new \Exception('Timetable conflict: section or teacher already has a class at this time.');
        }
        return $this->timetableRepository->update($id, $data);
    }

    public function deleteEntry(int $id): bool
    {
        return $this->timetableRepository->delete($id);
    }

    public function getSectionTimetable(int $sectionId, int $semesterId)
    {
        return $this->timetableRepository->getBySectionAndSemester($sectionId, $semesterId);
    }

    public function getTeacherTimetable(int $teacherId, int $semesterId)
    {
        return $this->timetableRepository->getByTeacherAndSemester($teacherId, $semesterId);
    }
}
