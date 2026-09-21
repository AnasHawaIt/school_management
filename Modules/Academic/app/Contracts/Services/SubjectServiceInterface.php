<?php

namespace App\Contracts\Services;

use App\Entities\Subject;

interface SubjectServiceInterface
{
    public function getAllSubjects(array $filters = []);
    public function getSubject(int $id);
    public function createSubject(array $data): Subject;
    public function updateSubject(int $id, array $data): Subject;
    public function deleteSubject(int $id): bool;
    public function restoreSubject(int $id): bool;
    public function getSubjectsByGrade(int $gradeId);
    public function assignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function unassignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function getSubjectWithTeachers(int $id);
    public function toggleStatus(int $id): Subject;
}
