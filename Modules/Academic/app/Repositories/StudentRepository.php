<?php

namespace Modules\Academic\app\Repositories;

use Modules\Academic\app\Contracts\Repositories\StudentRepositoryInterface;
use Modules\Academic\app\Entities\Student;
use Modules\School\Entities\Section;

class StudentRepository implements StudentRepositoryInterface
{
    public function __construct(protected Student $model) {}

    public function getAll(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'section.class.grade', 'academicYear']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['section_id'])) {
            $query->where('current_section_id', $filters['section_id']);
        }
        if (!empty($filters['academic_year_id'])) {
            $query->where('academic_year_id', $filters['academic_year_id']);
        }
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Student|null
    {
        return $this->model->with(['user', 'section.class.grade', 'academicYear'])->findOrFail($id);
    }

    public function findByStudentId(string $studentId)
    {
        return $this->model->where('student_id', $studentId)->firstOrFail();
    }

    public function create(array $data): Student
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Student
    {
        $student = $this->model->findOrFail($id);
        $student->update($data);
        return $student->fresh(['user', 'section.class.grade', 'academicYear']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function transferSection(int $studentId, int $newSectionId): bool
    {
        $student = $this->model->findOrFail($studentId);

        if ($student->current_section_id) {
            Section::findOrFail($student->current_section_id)->decrement('current_students');
        }

        $student->update(['current_section_id' => $newSectionId]);
        Section::findOrFail($newSectionId)->increment('current_students');

        return true;
    }

    public function promoteStudents(int $fromSectionId, int $toSectionId): int
    {
        $students = $this->model
            ->where('current_section_id', $fromSectionId)
            ->where('status', 'active')
            ->get();

        $count = $students->count();

        $students->each(fn($s) => $s->update(['current_section_id' => $toSectionId]));

        Section::findOrFail($fromSectionId)->decrement('current_students', $count);
        Section::findOrFail($toSectionId)->increment('current_students', $count);

        return $count;
    }

    public function getWithParents(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Student|null
    {
        return $this->model->with([
            'parents' => fn($q) => $q->withPivot('relationship', 'is_primary_contact', 'can_pickup'),
        ])->findOrFail($id);
    }

    public function getWithMedicalRecord(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Student|null
    {
        return $this->model->with('medicalRecord')->findOrFail($id);
    }

    public function generateStudentId(): string
    {
        $year     = now()->year;
        $last     = $this->model->withTrashed()->whereYear('created_at', $year)->count();
        $sequence = str_pad($last + 1, 5, '0', STR_PAD_LEFT);
        return "STU-{$year}-{$sequence}";
    }

    public function getBySection(int $sectionId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->with('user')
            ->where('current_section_id', $sectionId)
            ->where('status', 'active')
           // ->orderBy('first_name')
            ->get();
    }

    public function getStatsBySection(int $sectionId): array
    {
        $base = $this->model->where('current_section_id', $sectionId);
        return [
            'total'  => (clone $base)->count(),
            'active' => (clone $base)->where('status', 'active')->count(),
            'male'   => (clone $base)->whereHas('user', function($query) {
                $query->where('gender', 'male');
            })->count(),
            'female' => (clone $base)->whereHas('user', function($query) {
                $query->where('gender', 'female');
            })->count(),
        ];
    }
    public function assignStudent(int $sectionId, int $studentId, int $semesterId, int $academicYearId): bool
    {
        $student = $this->model->findOrFail($studentId);

        $student->sections()->syncWithoutDetaching([
            $sectionId => [
                'semester_id'      => $semesterId,
                'academic_year_id' => $academicYearId,
            ],
        ]);

        return true;
    }
}
