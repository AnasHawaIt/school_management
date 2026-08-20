<?php

namespace Modules\Academic\Repositories;

use Modules\Academic\Contracts\Repositories\TeacherRepositoryInterface;
use Modules\Academic\Entities\Teacher;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function __construct(protected Teacher $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['user', 'qualifications']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['specialization'])) {
            $query->where('specialization', 'like', "%{$filters['specialization']}%");
        }

        if (!empty($filters['contract_type'])) {
            $query->where('contract_type', $filters['contract_type']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with(['user', 'qualifications'])->findOrFail($id);
    }

    public function findByEmployeeId(string $employeeId)
    {
        return $this->model->where('employee_id', $employeeId)->firstOrFail();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $teacher = $this->model->findOrFail($id);
        $teacher->update($data);
        return $teacher->fresh(['user', 'qualifications']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function getWithQualifications(int $id)
    {
        return $this->model->with('qualifications')->findOrFail($id);
    }

    public function getWithSubjects(int $id)
    {
        return $this->model->with(['subjects.grade'])->findOrFail($id);
    }

    public function  getTeacherTimetable(int $teacherId, int $semesterId): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Teacher|null
    {
        return $this->model->with([
            'timetables' => fn($q) => $q
                ->where('semester_id', $semesterId)
                ->orderBy('day_of_week')
                ->orderBy('period_number'),
        ])->findOrFail($teacherId);
    }

    public function generateEmployeeId(): string
    {
        $year     = now()->year;
        $last     = $this->model->withTrashed()->whereYear('created_at', $year)->count();
        $sequence = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        return "EMP-{$year}-{$sequence}";
    }
}
