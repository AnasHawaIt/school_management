<?php

namespace Modules\Academic\Contracts\Services;

use Modules\Academic\Entities\Teacher;

interface TeacherServiceInterface
{
    public function getAllTeachers(array $filters = []);
    public function getTeacher(int $id);
    public function createTeacher(array $data): Teacher;
    public function updateTeacher(int $id, array $data): Teacher;
    public function deleteTeacher(int $id): bool;
    public function restoreTeacher(int $id): bool;
    public function getTeacherWithQualifications(int $id);
    public function addQualification(int $teacherId, array $data): object;
    public function deleteQualification(int $qualificationId): bool;
    public function getTeacherTimetable(int $teacherId, int $semesterId);
    public function toggleStatus(int $id): Teacher;
}
