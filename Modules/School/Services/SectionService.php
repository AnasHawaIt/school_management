<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\SectionRepositoryInterface;
use Modules\School\Contracts\Services\SectionServiceInterface;
use Modules\School\Entities\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SectionService implements SectionServiceInterface
{
    protected $repository;

    public function __construct(SectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->getActive();
    }

    public function getById(int $id): ?Section
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Section
    {
        DB::beginTransaction();
        try {
            if (!isset($data['current_students'])) {
                $data['current_students'] = 0;
            }

            $section = $this->repository->create($data);

            DB::commit();
            return $section;
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

    public function getByClass(int $classId): Collection
    {
        return $this->repository->getByClass($classId);
    }
}
