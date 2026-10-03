<?php

namespace Modules\Attendance\Contracts\Services;

use Modules\Attendance\Entities\StudentAttendance;

interface StudentAttendanceServiceInterface
{
    public function getAll(array $filters = []);
    public function getSectionAttendance(int $sectionId, string $date);
    public function recordAttendance(array $data): StudentAttendance;
    public function bulkRecord(int $sectionId, string $date, array $records): StudentAttendance;
    public function updateAttendance(int $id, array $data): StudentAttendance;
    public function deleteAttendance(int $id): bool;
    public function getStudentReport(int $studentId, array $filters = []);
    public function getStudentStats(int $studentId, int $semesterId): array;
    public function getSectionStats(int $sectionId, int $semesterId): array;
}
