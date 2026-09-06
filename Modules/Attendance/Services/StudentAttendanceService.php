<?php

namespace Modules\Attendance\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Contracts\Repositories\StudentAttendanceRepositoryInterface;
use Modules\Attendance\Contracts\Services\StudentAttendanceServiceInterface;
use Modules\Attendance\Entities\StudentAttendance;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceDeleted;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceRecorded;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceUpdated;

class StudentAttendanceService implements StudentAttendanceServiceInterface
{
    public function __construct(
        protected StudentAttendanceRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function getSectionAttendance(int $sectionId, string $date)
    {
        return $this->repository->getBySection($sectionId, $date);
    }

    public function recordAttendance(array $data): StudentAttendance
    {
        $userId = Auth::id();

        $data['recorded_by'] = $userId;

        $existing = $this->repository->findByStudentAndDate(
            $data['student_id'],
            $data['date']
        );

        if ($existing) {
            $oldStatus = $existing->status;

            $attendance = $this->repository->update(
                $existing->id,
                $data
            );

            event(new StudentAttendanceUpdated(
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

        event(new StudentAttendanceRecorded(
            attendance: $attendance,
            userId: $userId,
        ));

        return $attendance;
    }

    public function bulkRecord(
        int $sectionId,
        string $date,
        array $records
    ): StudentAttendance {
        $recordedBy = Auth::id();
        $timestamp = now()->toDateTimeString();

        $prepared = collect($records)
            ->map(fn ($record) => array_merge($record, [
                'section_id' => $sectionId,
                'date' => $date,
                'recorded_by' => $recordedBy,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]))
            ->toArray();

        $result = $this->repository->bulkCreate($prepared);

        if ($result) {
            event(new StudentAttendanceBulkRecorded(
                sectionId: $sectionId,
                date: $date,
                records: $prepared,
                userId: $recordedBy,
            ));

            return $result;
        }

        return $result;
    }

    public function updateAttendance(
        int $id,
        array $data
    ): StudentAttendance {
        $userId = Auth::id();

        $existing = $this->repository->findById($id);

        $oldStatus = $existing?->status;

        $attendance = $this->repository->update(
            $id,
            $data
        );

        event(new StudentAttendanceUpdated(
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
            event(new StudentAttendanceDeleted(
                attendance: $attendance,
                userId: $userId,
            ));
        }

        return $result;
    }

    public function getStudentReport(
        int $studentId,
        array $filters = []
    ) {
        return $this->repository->getByStudent(
            $studentId,
            $filters
        );
    }

    public function getStudentStats(
        int $studentId,
        int $semesterId
    ): array {
        return $this->repository->getStudentStats(
            $studentId,
            $semesterId
        );
    }

    public function getSectionStats(
        int $sectionId,
        int $semesterId
    ): array {
        return $this->repository->getSectionStats(
            $sectionId,
            $semesterId
        );
    }
}
