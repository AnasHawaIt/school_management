<?php

namespace Modules\Academic\Repositories;

use Modules\Academic\Contracts\Repositories\StudentPointRepositoryInterface;
use Modules\Academic\Entities\StudentPoint;
use Modules\Academic\Entities\Student;

class StudentPointRepository implements StudentPointRepositoryInterface
{
    public function __construct(protected StudentPoint $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with([
            'student.user', 'category', 'semester',
        ]);

        if (!empty($filters['student_id']))       $query->where('student_id', $filters['student_id']);
        if (!empty($filters['semester_id']))      $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['academic_year_id'])) $query->where('academic_year_id', $filters['academic_year_id']);
        if (!empty($filters['type']))             $query->where('type', $filters['type']);
        if (!empty($filters['category_id']))      $query->where('point_category_id', $filters['category_id']);
        if (!empty($filters['given_by_type']))    $query->where('given_by_type', $filters['given_by_type']);
        if (!empty($filters['given_by_id']))      $query->where('given_by_id', $filters['given_by_id']);
        if (!empty($filters['from_date']))        $query->where('date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))          $query->where('date', '<=', $filters['to_date']);

        return $query->latest()->paginate($filters['per_page'] ?? 20);
    }

    public function findById(int $id)
    {
        return $this->model->with(['student.user', 'category', 'semester'])->findOrFail($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function bulkCreate(array $records): bool
    {
        $timestamp = now()->toDateTimeString();
        $prepared  = collect($records)->map(fn($r) => array_merge($r, [
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]))->toArray();

        $this->model->insert($prepared);
        return true;
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getStudentTotal(int $studentId, int $semesterId): array
    {
        $base = fn() => $this->model
            ->where('student_id', $studentId)
            ->where('semester_id', $semesterId);

        $positive = (clone $base())->where('type', 'positive')->sum('points');
        $negative = (clone $base())->where('type', 'negative')->sum('points');
        $total    = $positive - $negative;

        return [
            'positive'   => (int) $positive,
            'negative'   => (int) $negative,
            'total'      => (int) $total,
            'by_category' => (clone $base())
                ->with('category')
                ->selectRaw('point_category_id, type, SUM(points) as total')
                ->groupBy('point_category_id', 'type')
                ->get()
                ->map(fn($r) => [
                    'category'  => $r->category?->name_ar,
                    'type'      => $r->type,
                    'total'     => $r->total,
                ]),
        ];
    }

    public function getStudentHistory(int $studentId, array $filters = [])
    {
        $query = $this->model->with(['category', 'semester'])
            ->where('student_id', $studentId);

        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['type']))        $query->where('type', $filters['type']);

        return $query->latest('date')->paginate($filters['per_page'] ?? 20);
    }

    public function getSectionRanking(int $sectionId, int $semesterId)
    {
        $students = Student::where('current_section_id', $sectionId)
            ->where('status', 'active')
            ->with('user')
            ->get();

        return $students->map(function ($student) use ($semesterId) {
            $positive = $this->model
                ->where('student_id', $student->id)
                ->where('semester_id', $semesterId)
                ->where('type', 'positive')
                ->sum('points');

            $negative = $this->model
                ->where('student_id', $student->id)
                ->where('semester_id', $semesterId)
                ->where('type', 'negative')
                ->sum('points');

            return [
                'student_id'  => $student->id,
                'student_code'=> $student->student_id,
                'full_name'   => $student->user->first_name . ' ' . $student->user->last_name,
                'positive'    => (int) $positive,
                'negative'    => (int) $negative,
                'total'       => (int) ($positive - $negative),
            ];
        })->sortByDesc('total')->values();
    }

    public function getStats(array $filters = []): array
    {
        $query = $this->model->query();

        if (!empty($filters['semester_id']))  $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['section_id'])) {
            $studentIds = Student::where('current_section_id', $filters['section_id'])->pluck('id');
            $query->whereIn('student_id', $studentIds);
        }

        return [
            'total_positive' => (clone $query)->where('type', 'positive')->sum('points'),
            'total_negative' => (clone $query)->where('type', 'negative')->sum('points'),
            'records_count'  => (clone $query)->count(),
            'by_category'    => (clone $query)
                ->with('category')
                ->selectRaw('point_category_id, SUM(points) as total, COUNT(*) as count')
                ->groupBy('point_category_id')
                ->get()
                ->map(fn($r) => [
                    'category' => $r->category?->name_ar,
                    'total'    => $r->total,
                    'count'    => $r->count,
                ]),
        ];
    }
}
