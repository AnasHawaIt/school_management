<?php

namespace Modules\Core\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ActivityLogRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(int $userId): Collection;

    public function getRecent(int $days = 7): Collection;

    public function getToday(): Collection;

    public function log(array $data): void;
}
