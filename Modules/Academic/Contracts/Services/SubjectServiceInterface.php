<?php

namespace Modules\Academic\Contracts\Services;

interface SubjectServiceInterface
{
    public function getAllSubjects(array $filters = []);
    public function getSubject(int $id);
    public function createSubject(array $data): object;
    public function updateSubject(int $id, array $data): object;
    public function deleteSubject(int $id): bool;
    public function restoreSubject(int $id): bool;
    public function getSubjectsByGrade(int $gradeId);
    public function assignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function unassignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function getSubjectWithTeachers(int $id);
    public function toggleStatus(int $id): object;
}
