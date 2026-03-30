<?php

namespace Modules\Core\Repositories;

use App\Repositories\BaseRepository;
use Modules\Core\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Core\Entities\ActivityLog;
use Illuminate\Database\Eloquent\Collection;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->byUser($userId)->latest()->get();
    }

    public function getRecent(int $days = 7): Collection
    {
        return $this->model->recent($days)->latest()->get();
    }

    public function getToday(): Collection
    {
        return $this->model->today()->latest()->get();
    }

    public function log(array $data): void
    {
        $this->create([
            'user_id' => auth()->id(),
            'action' => $data['action'],
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
