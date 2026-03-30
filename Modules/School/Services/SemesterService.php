<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\SemesterRepositoryInterface;
use Modules\School\Contracts\Services\SemesterServiceInterface;
use Modules\School\Entities\Semester;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SemesterService implements SemesterServiceInterface
{
    protected $repository;

    public function __construct(SemesterRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?Semester
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Semester
    {
        DB::beginTransaction();
        try {
            $semester = $this->repository->create($data);

            if (isset($data['is_current']) && $data['is_current']) {
                $semester->setCurrent();
            }

            DB::commit();
            return $semester;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $updated = $this->repository->update($id, $data);

            if (isset($data['is_current']) && $data['is_current']) {
                $semester = $this->repository->find($id);
                $semester->setCurrent();
            }

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getByAcademicYear(int $academicYearId): Collection
    {
        return $this->repository->getByAcademicYear($academicYearId);
    }

    public function setCurrent(int $id): bool
    {
        $semester = $this->repository->find($id);
        if ($semester) {
            $semester->setCurrent();
            return true;
        }
        return false;
    }
    public function getCurrent()
    {
        return $this->repository->getCurrent();
    }
}
