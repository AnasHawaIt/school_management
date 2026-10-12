<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\GradeRepositoryInterface;
use Modules\School\Contracts\Services\GradeServiceInterface;
use Modules\School\Entities\Grade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GradeService implements GradeServiceInterface
{
    protected $repository;

    public function __construct(GradeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->getOrdered();
    }

    public function getById(int $id): ?Grade
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Grade
    {
        DB::beginTransaction();
        try {
            $grade = $this->repository->create($data);

            DB::commit();
            return $grade;
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

    public function getByLevel(string $level): Collection
    {
        return $this->repository->getByLevel($level);
    }
}
