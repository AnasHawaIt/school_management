<?php

namespace Modules\Attendance\Contracts\Services;

interface StudentAttendanceServiceInterface
{
    public function getAll(array $filters = []);
    public function getSectionAttendance(int $sectionId, string $date);
    public function recordAttendance(array $data): object;
    public function bulkRecord(int $sectionId, string $date, array $records): bool;
    public function updateAttendance(int $id, array $data): object;
    public function deleteAttendance(int $id): bool;
    public function getStudentReport(int $studentId, array $filters = []);
    public function getStudentStats(int $studentId, int $semesterId): array;
    public function getSectionStats(int $sectionId, int $semesterId): array;
}
