<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\FeeTypeRepositoryInterface;
use Modules\Finance\Contracts\Services\FeeTypeServiceInterface;
use Modules\Finance\Entities\FeeType;
use Illuminate\Database\Eloquent\Collection;

class FeeTypeService implements FeeTypeServiceInterface
{
    public function __construct(protected FeeTypeRepositoryInterface $repository) {}

    public function getAll(): Collection { return $this->repository->all(); }
    public function getById(int $id): ?FeeType { return $this->repository->find($id); }
    public function create(array $data): FeeType { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }
}
