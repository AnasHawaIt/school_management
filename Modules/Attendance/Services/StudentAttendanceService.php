<?php

namespace Modules\Attendance\Services;

use Modules\Attendance\Contracts\Services\StudentAttendanceServiceInterface;
use Modules\Attendance\Contracts\Repositories\StudentAttendanceRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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

    public function recordAttendance(array $data): object
    {
        $data['recorded_by'] = Auth::id();
        $existing = $this->repository->findByStudentAndDate($data['student_id'], $data['date']);
        return $existing
            ? $this->repository->update($existing->id, $data)
            : $this->repository->create($data);
    }

    public function bulkRecord(int $sectionId, string $date, array $records): bool
    {
        $recordedBy = Auth::id();
        $timestamp  = now()->toDateTimeString();

        $prepared = collect($records)->map(fn($r) => array_merge($r, [
            'section_id'  => $sectionId,
            'date'        => $date,
            'recorded_by' => $recordedBy,
            'created_at'  => $timestamp,
            'updated_at'  => $timestamp,
        ]))->toArray();

        return $this->repository->bulkCreate($prepared);
    }

    public function updateAttendance(int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAttendance(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getStudentReport(int $studentId, array $filters = [])
    {
        return $this->repository->getByStudent($studentId, $filters);
    }

    public function getStudentStats(int $studentId, int $semesterId): array
    {
        return $this->repository->getStudentStats($studentId, $semesterId);
    }

    public function getSectionStats(int $sectionId, int $semesterId): array
    {
        return $this->repository->getSectionStats($sectionId, $semesterId);
    }
}
