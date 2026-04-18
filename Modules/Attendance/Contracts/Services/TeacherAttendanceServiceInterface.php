<?php

namespace Modules\Attendance\Contracts\Services;

interface TeacherAttendanceServiceInterface
{
    public function getAll(array $filters = []);
    public function getDailyAttendance(string $date);
    public function recordAttendance(array $data): object;
    public function updateAttendance(int $id, array $data): object;
    public function deleteAttendance(int $id): bool;
    public function getTeacherReport(int $teacherId, array $filters = []);
    public function getTeacherStats(int $teacherId, array $filters = []): array;
}
