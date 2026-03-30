<?php

namespace Modules\Academic\Contracts\Repositories;

interface TeacherRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function findByEmployeeId(string $employeeId);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function getWithQualifications(int $id);
    public function getWithSubjects(int $id);
    public function getTeacherTimetable(int $teacherId, int $semesterId);
    public function generateEmployeeId(): string;
}
