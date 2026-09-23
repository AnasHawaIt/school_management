<?php

namespace Modules\Academic\app\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Academic\app\Contracts\Repositories\CounselorRepositoryInterface;
use Modules\Academic\app\Entities\Counselor;

class CounselorRepository implements CounselorRepositoryInterface
{
    public function __construct(protected Counselor $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with(['user', 'sections']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', fn($q) => $q
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
            )->orWhere('counselor_id', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with(['user', 'sections.class.grade'])->findOrFail($id);
    }

    public function create(array $data): Counselor
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Counselor
    {
        $counselor = $this->model->findOrFail($id);
        $counselor->update($data);
        return $counselor->fresh(['user', 'sections']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function assignSection(int $counselorId, int $sectionId, int $academicYearId): bool
    {
        $this->model->findOrFail($counselorId)
            ->sections()
            ->syncWithoutDetaching([
                $sectionId => ['academic_year_id' => $academicYearId],
            ]);
        return true;
    }

    public function unassignSection(int $counselorId, int $sectionId, int $academicYearId): bool
    {
        DB::table('counselor_section')
            ->where('counselor_id', $counselorId)
            ->where('section_id', $sectionId)
            ->where('academic_year_id', $academicYearId)
            ->delete();
        return true;
    }

    public function getSections(int $counselorId, int $academicYearId)
    {
        return $this->model->findOrFail($counselorId)
            ->sections()
            ->wherePivot('academic_year_id', $academicYearId)
            ->with('class.grade')
            ->get();
    }

    public function generateCounselorId(): string
    {
        $year     = now()->year;
        $last     = $this->model->withTrashed()->whereYear('created_at', $year)->count();
        $sequence = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        return "CNS-{$year}-{$sequence}";
    }
}
