<?php

namespace Modules\Academic\Contracts\Repositories;

interface SubjectRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function findByCode(string $code);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function getByGrade(int $gradeId);
    public function assignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function unassignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool;
    public function getWithTeachers(int $id);
}
