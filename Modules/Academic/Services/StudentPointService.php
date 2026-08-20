<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Repositories\StudentPointRepositoryInterface;
use Modules\Academic\Entities\PointCategory;
use Modules\Academic\Entities\StudentPoint;
use Modules\Attendance\Entities\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class StudentPointService
{
    public function __construct(
        protected StudentPointRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }


    public function givePoint(array $data): object
    {
        $category = PointCategory::findOrFail($data['point_category_id']);

        return $this->repository->create(array_merge($data, [
            'type'         => $category->type,
            'points'       => $data['points'] ?? $category->default_points,
            'date'         => $data['date'] ?? now()->format('Y-m-d'),
        ]));
    }


    public function bulkGive(array $data): bool
    {
        $category  = PointCategory::findOrFail($data['point_category_id']);
        $timestamp = now()->toDateTimeString();

        $records = collect($data['student_ids'])->map(fn($studentId) => [
            'student_id'          => $studentId,
            'point_category_id'   => $data['point_category_id'],
            'academic_year_id'    => $data['academic_year_id'],
            'semester_id'         => $data['semester_id'],
            'type'                => $category->type,
            'points'              => $data['points'] ?? $category->default_points,
            'reason'              => $data['reason'],
            'date'                => $data['date'] ?? now()->format('Y-m-d'),
            'given_by_type'       => $data['given_by_type'],
            'given_by_id'         => $data['given_by_id'],
            'inspection_program_id' => $data['inspection_program_id'] ?? null,
            'notes'               => $data['notes'] ?? null,
        ])->toArray();

        return $this->repository->bulkCreate($records);
    }

    // ربط النقاط بالحضور تلقائياً
    // يُستدعى بعد تسجيل الحضور
    public function autoAssignFromAttendance(StudentAttendance $attendance): void
    {
        $categories = PointCategory::where('auto_assign', true)->where('is_active', true)->get();

        foreach ($categories as $category) {
            $shouldAssign = match($category->name) {
                'Absence'   => $attendance->status->code === 'A',
                'Late'      => $attendance->status->code === 'L',
                'Attendance'=> $attendance->status->code === 'P',
                default     => false,
            };

            if (!$shouldAssign) continue;


            $exists = StudentPoint::where([
                'student_id'           => $attendance->student_id,
                'student_attendance_id'=> $attendance->id,
                'point_category_id'    => $category->id,
            ])->exists();

            if ($exists) continue;

            $this->repository->create([
                'student_id'            => $attendance->student_id,
                'point_category_id'     => $category->id,
                'academic_year_id'      => $attendance->academic_year_id,
                'semester_id'           => $attendance->semester_id,
                'type'                  => $category->type,
                'points'                => $category->default_points,
                'reason'                => "Auto: {$category->name_ar}",
                'date'                  => $attendance->date->format('Y-m-d'),
                'given_by_type'         => 'counselor',
                'given_by_id'           => 1, // system/admin
                'student_attendance_id' => $attendance->id,
            ]);
        }
    }

    public function deletePoint(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getStudentTotal(int $studentId, int $semesterId): array
    {
        return $this->repository->getStudentTotal($studentId, $semesterId);
    }

    public function getStudentHistory(int $studentId, array $filters = [])
    {
        return $this->repository->getStudentHistory($studentId, $filters);
    }

    public function getSectionRanking(int $sectionId, int $semesterId)
    {
        return $this->repository->getSectionRanking($sectionId, $semesterId);
    }

    public function getStats(array $filters = []): array
    {
        return $this->repository->getStats($filters);
    }
}
