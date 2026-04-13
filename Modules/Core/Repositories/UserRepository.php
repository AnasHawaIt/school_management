<?php

namespace Modules\Core\Repositories;

use App\Repositories\BaseRepository;
use Modules\Core\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\Entities\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->byType($type)->get();
    }

    public function searchByName(string $name): Collection
    {
        return $this->model
            ->where('name', 'like', "%{$name}%")
            ->orWhere('email', 'like', "%{$name}%")
            ->get();
    }

    public function getWithRoles(int $id)
    {
        return $this->model->with('roles.permissions')->find($id);
    }

    public function getWithFilters(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name_ar', 'like', "%{$search}%")
                    ->orWhere('last_name_ar', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"); // اختياري: البحث برقم الهاتف أيضاً
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
