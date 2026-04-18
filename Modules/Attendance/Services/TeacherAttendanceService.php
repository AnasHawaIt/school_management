<?php

namespace Modules\Attendance\Services;

use Modules\Attendance\Contracts\Services\TeacherAttendanceServiceInterface;
use Modules\Attendance\Contracts\Repositories\TeacherAttendanceRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TeacherAttendanceService implements TeacherAttendanceServiceInterface
{
    public function __construct(
        protected TeacherAttendanceRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getDailyAttendance(string $date)
    {
        return $this->repository->getByDate($date);
    }

    public function recordAttendance(array $data): object
    {
        $data['recorded_by'] = Auth::id();

        $existing = $this->repository->findByTeacherAndDate($data['teacher_id'], $data['date']);

        return $existing
            ? $this->repository->update($existing->id, $data)
            : $this->repository->create($data);
    }

    public function updateAttendance(int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAttendance(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTeacherReport(int $teacherId, array $filters = [])
    {
        return $this->repository->getByTeacher($teacherId, $filters);
    }

    public function getTeacherStats(int $teacherId, array $filters = []): array
    {
        return $this->repository->getTeacherStats($teacherId, $filters);
    }
}
