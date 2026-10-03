<?php

namespace Modules\Attendance\Contracts\Services;

use Modules\Attendance\Entities\TeacherAttendance;

interface TeacherAttendanceServiceInterface
{
    public function getAll(array $filters = []);
    public function getDailyAttendance(string $date);
    public function recordAttendance(array $data): TeacherAttendance;
    public function updateAttendance(int $id, array $data): TeacherAttendance;
    public function deleteAttendance(int $id): bool;
    public function getTeacherReport(int $teacherId, array $filters = []);
    public function getTeacherStats(int $teacherId, array $filters = []): array;
}
