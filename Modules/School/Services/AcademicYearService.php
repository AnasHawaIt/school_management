<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\AcademicYearRepositoryInterface;
use Modules\School\Contracts\Services\AcademicYearServiceInterface;
use Modules\School\Entities\AcademicYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AcademicYearService implements AcademicYearServiceInterface
{
    protected $repository;

    public function __construct(AcademicYearRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?AcademicYear
    {
        return $this->repository->find($id);
    }

    public function create(array $data): AcademicYear
    {
        DB::beginTransaction();
        try {
            $academicYear = $this->repository->create($data);

            // If this is set as current, update others
            if (isset($data['is_current']) && $data['is_current']) {
                $academicYear->setCurrent();
            }

            DB::commit();
            return $academicYear;
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
                $academicYear = $this->repository->find($id);
                $academicYear->setCurrent();
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

    public function setCurrent(int $id): bool
    {
        $academicYear = $this->repository->find($id);
        if ($academicYear) {
            $academicYear->setCurrent();
            return true;
        }
        return false;
    }

    public function getCurrent()
    {
        return $this->repository->getCurrent();
    }
}
