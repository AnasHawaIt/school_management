<?php

namespace Modules\Attendance\Contracts\Repositories;

interface StudentAttendanceRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
    public function bulkCreate(array $records): bool;
    public function getBySection(int $sectionId, string $date);
    public function getByStudent(int $studentId, array $filters = []);
    public function getStudentStats(int $studentId, int $semesterId): array;
    public function getSectionStats(int $sectionId, int $semesterId): array;
    public function findByStudentAndDate(int $studentId, string $date);
}
