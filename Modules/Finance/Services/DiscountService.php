<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\DiscountRepositoryInterface;
use Modules\Finance\Contracts\Services\DiscountServiceInterface;
use Modules\Finance\Entities\Discount;
use Illuminate\Database\Eloquent\Collection;

class DiscountService implements DiscountServiceInterface
{
    public function __construct(protected DiscountRepositoryInterface $repository) {}

    public function getAll(): Collection { return $this->repository->all(); }
    public function getById(int $id): ?Discount { return $this->repository->find($id); }
    public function create(array $data): Discount { return $this->repository->create($data); }
    public function update(int $id, array $data): bool { return $this->repository->update($id, $data); }
    public function delete(int $id): bool { return $this->repository->delete($id); }
}
