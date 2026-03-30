<?php

namespace Modules\Academic\Contracts\Services;

interface TimetableServiceInterface
{
    public function getAllTimetables(array $filters = []);
    public function getTimetable(int $id);
    public function createEntry(array $data): object;
    public function updateEntry(int $id, array $data): object;
    public function deleteEntry(int $id): bool;
    public function getSectionTimetable(int $sectionId, int $semesterId);
    public function getTeacherTimetable(int $teacherId, int $semesterId);
}
