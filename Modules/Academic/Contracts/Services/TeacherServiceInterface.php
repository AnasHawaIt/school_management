<?php

namespace Modules\Academic\Contracts\Services;

interface TeacherServiceInterface
{
    public function getAllTeachers(array $filters = []);
    public function getTeacher(int $id);
    public function createTeacher(array $data): object;
    public function updateTeacher(int $id, array $data): object;
    public function deleteTeacher(int $id): bool;
    public function restoreTeacher(int $id): bool;
    public function getTeacherWithQualifications(int $id);
    public function addQualification(int $teacherId, array $data): object;
    public function deleteQualification(int $qualificationId): bool;
    public function getTeacherTimetable(int $teacherId, int $semesterId);
    public function toggleStatus(int $id): object;
}
