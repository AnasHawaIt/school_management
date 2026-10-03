<?php

namespace Modules\Academic\Contracts\Services;

use Modules\Academic\Entities\Timetable;

interface TimetableServiceInterface
{
    public function getAllTimetables(array $filters = []);
    public function getTimetable(int $id);
    public function createEntry(array $data): Timetable;
    public function updateEntry(int $id, array $data): Timetable;
    public function deleteEntry(int $id): bool;
    public function getSectionTimetable(int $sectionId, int $semesterId);
    public function getTeacherTimetable(int $teacherId, int $semesterId);
}
