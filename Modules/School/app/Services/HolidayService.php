<?php

namespace Modules\School\Services;

use Modules\School\Contracts\Repositories\HolidayRepositoryInterface;
use Modules\School\Contracts\Services\HolidayServiceInterface;
use Modules\School\Entities\Holiday;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HolidayService implements HolidayServiceInterface
{
    protected $repository;

    public function __construct(HolidayRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?Holiday
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Holiday
    {
        DB::beginTransaction();
        try {
            $holiday = $this->repository->create($data);

            DB::commit();
            return $holiday;
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

    public function getUpcoming(): Collection
    {
        return $this->repository->getUpcoming();
    }
}
