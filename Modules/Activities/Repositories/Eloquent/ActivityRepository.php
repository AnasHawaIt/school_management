<?php


namespace Modules\Activities\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Repositories\Interfaces\ActivityRepositoryInterface;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function __construct(
        protected Activity $model
    )
    {
    }

    public function findById(
        int   $id,
        array $with = []
    ): ?Activity
    {
        return $this->model
            ->with($with)
            ->find($id);
    }

    public function findOrFail(
        int   $id,
        array $with = []
    ): Activity
    {
        return $this->model
            ->with($with)
            ->findOrFail($id);
    }

    public function paginate(
        int   $perPage = 15,
        array $filters = [],
        array $with = []
    ): LengthAwarePaginator
    {
        $query = $this->model
            ->with($with)
            ->when(
                $filters['category_id'] ?? null,
                fn($query, $categoryId) => $query->where('category_id', $categoryId)
            )
            ->when(
                $filters['status'] ?? null,
                fn($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['is_featured'] ?? null,
                fn($query, $featured) => $query->where('is_featured', $featured)
            )
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('title_ar', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $filters['from'] ?? null,
                fn($query, $from) => $query->whereDate('start_at', '>=', $from)
            )
            ->when(
                $filters['to'] ?? null,
                fn($query, $to) => $query->whereDate('start_at', '<=', $to)
            );

        return $query
            ->orderBy('start_at')
            ->paginate($perPage);
    }

    public function create(array $data): Activity
    {
        return $this->model->create($data);
    }

    public function update(
        Activity $activity,
        array    $data
    ): Activity
    {
        $activity->update($data);

        return $activity->refresh();
    }

    public function delete(Activity $activity): bool
    {
        return (bool)$activity->delete();
    }
}
