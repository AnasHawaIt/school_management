<?php

namespace Modules\Academic\app\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Academic\app\Contracts\Repositories\StudentPointRepositoryInterface;
use Modules\Academic\app\Entities\PointCategory;
use Modules\Academic\app\Entities\StudentPoint;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointGiven;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointsBulkGiven;
use Modules\Attendance\app\Entities\StudentAttendance;

class StudentPointService
{
    public function __construct(
        protected StudentPointRepositoryInterface $repository,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }


    public function givePoint(array $data): StudentPoint
    {
        $category = PointCategory::findOrFail(
            $data['point_category_id']
        );

        $studentPoint = $this->repository->create(
            array_merge($data, [
                'type'   => $category->type,
                'points' => $data['points'] ?? $category->default_points,
                'date'   => $data['date'] ?? now()->format('Y-m-d'),
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new StudentPointGiven(
            studentPoint: $studentPoint,
            userId: Auth::id(),
        ));

        return $studentPoint;
    }


    public function bulkGive(array $data): bool
    {
        $category = PointCategory::findOrFail(
            $data['point_category_id']
        );

        $points = $data['points']
            ?? $category->default_points;

        $records = collect($data['student_ids'])
            ->map(fn ($studentId) => [
                'student_id'            => $studentId,
                'point_category_id'     => $data['point_category_id'],
                'academic_year_id'      => $data['academic_year_id'],
                'semester_id'           => $data['semester_id'],
                'type'                  => $category->type,
                'points'                => $points,
                'reason'                => $data['reason'],
                'date'                  => $data['date']
                    ?? now()->format('Y-m-d'),
                'given_by_type'         => $data['given_by_type'],
                'given_by_id'           => $data['given_by_id'],
                'inspection_program_id' => $data['inspection_program_id']
                    ?? null,
                'notes'                 => $data['notes'] ?? null,
            ])
            ->toArray();

        $result = $this->repository->bulkCreate($records);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($result) {
            event(new StudentPointsBulkGiven(
                studentIds: $data['student_ids'],
                pointCategoryId: $data['point_category_id'],
                points: $points,
                userId: Auth::id(),
            ));
        }

        return $result;
    }

    /**
     * Automatically assign points based on attendance.
     */
    public function autoAssignFromAttendance(
        StudentAttendance $attendance
    ): void {
        $categories = PointCategory::query()
            ->where('auto_assign', true)
            ->where('is_active', true)
            ->get();

        foreach ($categories as $category) {
            $shouldAssign = match ($category->name) {
                'Absence' => $attendance->status->code === 'A',
                'Late'    => $attendance->status->code === 'L',
                'Attendance' => $attendance->status->code === 'P',
                default => false,
            };
            if (! $shouldAssign) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate points
            |--------------------------------------------------------------------------
            */

            $exists = StudentPoint::query()
                ->where([
                    'student_id'            => $attendance->student_id,
                    'student_attendance_id' => $attendance->id,
                    'point_category_id'     => $category->id,
                ])
                ->exists();

            if ($exists) {
                continue;
            }

            $studentPoint = $this->repository->create([
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


            /*
            |--------------------------------------------------------------------------
            | Event
            |--------------------------------------------------------------------------
            |
            | This can be changed later to StudentPointAutoAssigned
            | if you want a separate audit event for automatic points.
            |
            */

            event(new StudentPointGiven(
                studentPoint: $studentPoint,
                userId: null,
            ));
        }
    }

    public function deletePoint(int $id): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Get model before deleting
        |--------------------------------------------------------------------------
        */

        $studentPoint = $this->repository->findById($id);

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $result = $this->repository->delete($id);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        if ($result) {
            event(new StudentPointDeleted(
                studentPoint: $studentPoint,
                userId: Auth::id(),
            ));
        }

        return $result;
    }

    public function getStudentTotal(
        int $studentId,
        int $semesterId
    ): array {
        return $this->repository->getStudentTotal(
            $studentId,
            $semesterId
        );
    }

    public function getStudentHistory(
        int $studentId,
        array $filters = []
    ) {
        return $this->repository->getStudentHistory(
            $studentId,
            $filters
        );
    }

    public function getSectionRanking(
        int $sectionId,
        int $semesterId
    ) {
        return $this->repository->getSectionRanking(
            $sectionId,
            $semesterId
        );
    }


    public function getStats(array $filters = []): array
    {
        return $this->repository->getStats($filters);
    }
}
