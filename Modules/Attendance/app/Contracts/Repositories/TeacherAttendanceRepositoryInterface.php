<?php

namespace Modules\Attendance\app\Contracts\Repositories;

interface TeacherAttendanceRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function getByTeacher(int $teacherId, array $filters = []);
    public function getByDate(string $date);
    public function getTeacherStats(int $teacherId, array $filters = []): array;
    public function findByTeacherAndDate(int $teacherId, string $date);
}
