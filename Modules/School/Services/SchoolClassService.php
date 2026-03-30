<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\SchoolClassRepositoryInterface;
use Modules\School\Contracts\Services\SchoolClassServiceInterface;
use Modules\School\Entities\SchoolClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SchoolClassService implements SchoolClassServiceInterface
{
    protected $repository;

    public function __construct(SchoolClassRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->getActive();
    }

    public function getById(int $id): ?SchoolClass
    {
        return $this->repository->getWithSections($id);
    }

    public function create(array $data): SchoolClass
    {
        DB::beginTransaction();
        try {
            $class = $this->repository->create($data);

            DB::commit();
            return $class;
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

    public function getByGrade(int $gradeId): Collection
    {
        return $this->repository->getByGrade($gradeId);
    }
}
