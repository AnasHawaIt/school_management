<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\FeeStructureRepositoryInterface;
use Modules\Finance\Contracts\Services\FeeStructureServiceInterface;
use Modules\Finance\Entities\FeeStructure;
use Illuminate\Database\Eloquent\Collection;

class FeeStructureService implements FeeStructureServiceInterface
{
    public function __construct(protected FeeStructureRepositoryInterface $repository) {}

    public function getAll(): Collection { return $this->repository->all(); }
    public function getById(int $id): ?FeeStructure { return $this->repository->find($id); }
    public function create(array $data): FeeStructure { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }
}
