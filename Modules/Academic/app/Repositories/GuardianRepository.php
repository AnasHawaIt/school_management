<?php

namespace Modules\Academic\app\Repositories;

use Modules\Academic\app\Contracts\Repositories\GuardianRepositoryInterface;
use Modules\Academic\app\Entities\Guardian;

class GuardianRepository implements GuardianRepositoryInterface
{
    public function __construct(protected Guardian $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with('user');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->model->with('user')->findOrFail($id);
    }

    public function create(array $data): Guardian
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Guardian
    {
        $guardian = $this->model->findOrFail($id);
        $guardian->update($data);
        return $guardian->fresh('user');
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function restore(int $id): bool
    {
        $user = $this->model->withTrashed()->findOrFail($id);

        // إذا كان العمود قيمته null، يعني السجل غير محذوف
        if (!$user->trashed()) {
            return false ; // سنستخدم كلمة مفتاحية لنفحصها في الكنترولر
        }

        return $user->restore(); // سيرجع true إذا تمت الاستعادة بنجاح
    }

    public function attachStudent(int $guardianId, int $studentId, array $pivotData): bool
    {
        $this->model->findOrFail($guardianId)
            ->students()
            ->syncWithoutDetaching([$studentId => $pivotData]);
        return true;
    }

    public function detachStudent(int $guardianId, int $studentId): bool
    {
        $this->model->findOrFail($guardianId)->students()->detach($studentId);
        return true;
    }

    public function getWithStudents(int $id)
    {
        return $this->model->with([
            'students' => fn($q) => $q
                ->withPivot('relationship', 'is_primary_contact', 'can_pickup')
                ->with('section.class.grade'),
        ])->findOrFail($id);
    }
}
