<?php

namespace Modules\Academic\app\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Academic\app\Contracts\Repositories\SubjectRepositoryInterface;
use Modules\Academic\app\Entities\Subject;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function __construct(protected Subject $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with('grade');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['grade_id'])) {
            $query->where('grade_id', $filters['grade_id']);
        }
        if (isset($filters['is_mandatory'])) {
            $query->where('is_mandatory', filter_var($filters['is_mandatory'], FILTER_VALIDATE_BOOLEAN));
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with('grade')->findOrFail($id);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->firstOrFail();
    }

    public function create(array $data): Subject
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Subject
    {
        $subject = $this->model->findOrFail($id);
        $subject->update($data);
        return $subject->fresh('grade');
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function getByGrade(int $gradeId)
    {
        return $this->model->with('grade')
            ->where('grade_id', $gradeId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function assignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool
    {
        $this->model->findOrFail($subjectId)
            ->teachers()
            ->syncWithoutDetaching([
                $teacherId => [
                    'section_id'       => $sectionId,
                    'academic_year_id' => $academicYearId,
                ],
            ]);
        return true;
    }

    public function unassignTeacher(int $subjectId, int $teacherId, int $sectionId, int $academicYearId): bool
    {
        DB::table('subject_teacher')
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->where('section_id', $sectionId)
            ->where('academic_year_id', $academicYearId)
            ->delete();
        return true;
    }

    public function getWithTeachers(int $id)
    {
        return $this->model->with([
            'teachers' => fn($q) => $q->withPivot('section_id', 'academic_year_id'),
        ])->findOrFail($id);
    }
}
