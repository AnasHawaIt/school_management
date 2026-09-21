<?php

namespace App\Contracts\Repositories;

use App\Entities\Timetable;

interface TimetableRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): Timetable;
    public function update(int $id, array $data): Timetable;
    public function delete(int $id): bool;
    public function getBySectionAndSemester(int $sectionId, int $semesterId);
    public function getByTeacherAndSemester(int $teacherId, int $semesterId);
    public function checkConflict(array $data, ?int $excludeId = null): bool;
}
