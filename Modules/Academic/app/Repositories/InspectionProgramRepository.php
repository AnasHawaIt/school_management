<?php

namespace App\Repositories;

use App\Contracts\Repositories\InspectionProgramRepositoryInterface;
use App\Entities\InspectionProgram;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InspectionProgramRepository implements InspectionProgramRepositoryInterface
{
    public function __construct(protected InspectionProgram $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['section.class.grade', 'counselors.user', 'semester']);

        if (!empty($filters['section_id']))       $query->where('section_id', $filters['section_id']);
        if (!empty($filters['semester_id']))      $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['academic_year_id'])) $query->where('academic_year_id', $filters['academic_year_id']);
        if (!empty($filters['status']))           $query->where('status', $filters['status']);
        if (!empty($filters['type']))             $query->where('type', $filters['type']);
        if (!empty($filters['from_date']))        $query->where('inspection_date', '>=', $filters['from_date']);
        if (!empty($filters['to_date']))          $query->where('inspection_date', '<=', $filters['to_date']);

        return $query->orderBy('inspection_date', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with([
            'section.class.grade',
            'counselors.user',
            'semester',
            'academicYear',
            'creator',
        ])->findOrFail($id);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $program = $this->model->findOrFail($id);
        $program->update($data);
        return $program->fresh(['section', 'counselors.user', 'semester']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function assignCounselor(int $programId, int $counselorId, string $role): bool
    {
        $this->model->findOrFail($programId)
            ->counselors()
            ->syncWithoutDetaching([
                $counselorId => ['role' => $role],
            ]);
        return true;
    }

    public function unassignCounselor(int $programId, int $counselorId): bool
    {
        $this->model->findOrFail($programId)->counselors()->detach($counselorId);
        return true;
    }

    public function updateCounselorObservation(int $programId, int $counselorId, array $data): bool
    {
        DB::table('inspection_program_counselor')
            ->where('inspection_program_id', $programId)
            ->where('counselor_id', $counselorId)
            ->update($data);
        return true;
    }

    public function updateStatus(int $id, string $status): object
    {
        $program = $this->model->findOrFail($id);
        $program->update(['status' => $status]);
        return $program->fresh();
    }

    public function getBySection(int $sectionId, array $filters = [])
    {
        $query = $this->model->with(['counselors.user', 'semester'])
            ->where('section_id', $sectionId);

        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);
        if (!empty($filters['status']))      $query->where('status', $filters['status']);

        return $query->orderBy('inspection_date', 'desc')->get();
    }

    public function getByCounselor(int $counselorId, array $filters = [])
    {
        $query = $this->model->with(['section.class.grade', 'semester'])
            ->whereHas('counselors', fn($q) => $q->where('counselors.id', $counselorId));

        if (!empty($filters['status']))      $query->where('status', $filters['status']);
        if (!empty($filters['semester_id'])) $query->where('semester_id', $filters['semester_id']);

        return $query->orderBy('inspection_date', 'desc')->get();
    }

    public function getCurrentCounselorProgram(int $counselorId)
    {
        return $this->model
            ->with([
                'section.class.grade',
                'counselors.user',
                'semester',
                'academicYear',
                'creator',
            ])
            ->whereHas('counselors', function ($query) use ($counselorId) {
                $query->where('counselors.id', $counselorId);
            })
            ->where('is_current', true)
            ->first();
    }
    public function setCurrent(int $id): bool
    {

        $this->model->where('is_current', true)->update(['is_current' => false]);

        $this->model->findOrFail($id)->update(['is_current' => true]);

        return true;
    }
}
