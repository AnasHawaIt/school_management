<?php

namespace Modules\Academic\Services;

use Modules\Academic\Contracts\Services\GuardianServiceInterface;
use Modules\Academic\Contracts\Repositories\GuardianRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User;


class GuardianService implements GuardianServiceInterface
{
    public function __construct(
        protected GuardianRepositoryInterface $guardianRepository,
    ) {}

    public function getAllGuardians(array $filters = [])
    {
        return $this->guardianRepository->getAll($filters)
            ->through(fn ($guardian) => $guardian->load(['students', 'user']));
    }
    public function getGuardian(int $id)
    {
        return $this->guardianRepository
            ->findById($id)
            ->load(['students', 'user']);
    }

    public function createGuardian(array $data): object
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'     => $data['first_name'],
                'last_name'     => $data['last_name'],
                'first_name_ar' => $data['first_name_ar'],
                'last_name_ar'     => $data['last_name_ar'],
                'gender'     => $data['gender'],
                'email'=>$data['email'],
                'date_of_birth'     => $data['date_of_birth'],
                'phone'     => $data['phone'],
                'avatar' => $data['avatar'],
                'password' => Hash::make($data['password'] ?? 'Parent@123'),
                'user_type'     => 'parent',
            ]);

            $data['user_id'] = $user->id;
            $guardian = $this->guardianRepository->create($data);

            if (!empty($data['students'])) {
                foreach ($data['students'] as $s) {
                    $this->guardianRepository->attachStudent($guardian->id, $s['student_id'], [
                        'relationship'       => $s['relationship'],
                        'is_primary_contact' => $s['is_primary_contact'] ?? false,
                        'can_pickup'         => $s['can_pickup'] ?? true,
                    ]);
                }
            }

            $user->assignRole('parent');

            return $guardian->load(['user', 'students']);
        });
    }

    public function updateGuardian(int $id, array $data): object
    {
        return DB::transaction(function () use ($id, $data) {
            $guardian   = $this->guardianRepository->update($id, $data);
            $userUpdate = [];

            if (!empty($data['email'])) {
                $userUpdate['email'] = $data['email'];
                $userUpdate['name']  = ($data['first_name'] ?? $guardian->first_name)
                    . ' ' . ($data['last_name'] ?? $guardian->last_name);
            }
            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make($data['password']);
            }
            if (!empty($userUpdate)) {
                $guardian->user->update($userUpdate);
            }

            return $guardian;
        });
    }

    public function deleteGuardian(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $guardian = $this->guardianRepository->findById($id);
            $guardian->user->update(['status' => 'inactive']);
            return $this->guardianRepository->delete($id);
        });
    }

    public function restoreGuardian(int $id): bool
    {
        return $this->guardianRepository->restore($id);
    }

    public function attachStudent(int $guardianId, int $studentId, array $pivotData): bool
    {
        return $this->guardianRepository->attachStudent($guardianId, $studentId, $pivotData);
    }

    public function detachStudent(int $guardianId, int $studentId): bool
    {
        return $this->guardianRepository->detachStudent($guardianId, $studentId);
    }

    public function getGuardianWithStudents(int $id)
    {
        return $this->guardianRepository->getWithStudents($id);
    }
}
