<?php

namespace Modules\Attendance\app\Services;

use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use App\Events\TeacherAttendance\TeacherAttendanceRecorded;
use App\Events\TeacherAttendance\TeacherAttendanceUpdated;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\app\Contracts\Repositories\TeacherAttendanceRepositoryInterface;
use Modules\Attendance\app\Contracts\Services\TeacherAttendanceServiceInterface;
use Modules\Attendance\app\Entities\TeacherAttendance;

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

    public function recordAttendance(array $data): TeacherAttendance
    {
        $userId = Auth::id();

        $data['recorded_by'] = $userId;

        $existing = $this->repository->findByTeacherAndDate(
            $data['teacher_id'],
            $data['date']
        );

        if ($existing) {
            $oldStatus = $existing->status;

            $attendance = $this->repository->update(
                $existing->id,
                $data
            );

            event(new TeacherAttendanceUpdated(
                attendance: $attendance,
                changes: [
                    'old_status' => $oldStatus,
                    ...$attendance->getChanges(),
                ],
                userId: $userId,
            ));

            return $attendance;
        }

        $attendance = $this->repository->create($data);

        event(new TeacherAttendanceRecorded(
            attendance: $attendance,
            userId: $userId,
        ));

        return $attendance;
    }


    public function updateAttendance(
        int $id,
        array $data
    ): TeacherAttendance {
        $userId = Auth::id();

        $existing = $this->repository->findById($id);

        $oldStatus = $existing?->status;

        $attendance = $this->repository->update(
            $id,
            $data
        );

        event(new TeacherAttendanceUpdated(
            attendance: $attendance,
            changes: [
                'old_status' => $oldStatus,
                ...$attendance->getChanges(),
            ],
            userId: $userId,
        ));

        return $attendance;
    }

    public function deleteAttendance(int $id): bool
    {
        $userId = Auth::id();

        $attendance = $this->repository->findById($id);

        if (!$attendance) {
            return false;
        }

        $result = $this->repository->delete($id);

        if ($result) {
            event(new TeacherAttendanceDeleted(
                attendance: $attendance,
                userId: $userId,
            ));
        }

        return $result;
    }

    public function getTeacherReport(
        int $teacherId,
        array $filters = []
    ) {
        return $this->repository->getByTeacher(
            $teacherId,
            $filters
        );
    }

    public function getTeacherStats(
        int $teacherId,
        array $filters = []
    ): array {
        return $this->repository->getTeacherStats(
            $teacherId,
            $filters
        );
    }
}
