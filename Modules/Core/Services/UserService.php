<?php

namespace Modules\Core\Services;

use Modules\Core\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Core\Contracts\Services\UserServiceInterface;
use Modules\Core\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $activityLogRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ActivityLogRepositoryInterface $activityLogRepository
    ) {
        $this->userRepository = $userRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->getWithFilters($filters);
    }

    public function getUser(int $id): ?User
    {
        return $this->userRepository->getWithRoles($id);
    }

    public function createUser(array $data): User
    {
        DB::beginTransaction();

        try {
            // Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
                // تخزين الصورة في مجلد 'avatars' داخل الـ public disk
                $path = $data['avatar']->store('avatars', 'public');
                // استبدال كائن الملف بالمسار النصي لتخزينه في الداتابيز
                $data['avatar'] = $path;
            }
            // Create user
            $user = $this->userRepository->create($data);

            // Assign role if provided
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'create',
                'model_type' => User::class,
              //  'model_id' => $user->id,
                'new_values' => $user->toArray(),
            ]);

            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->findOrFail($id);
            $oldValues = $user->toArray();

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Update user
            $updated = $this->userRepository->update($id, $data);

            // Update role if provided
            if (isset($data['role'])) {
                $user->roles()->sync([]);
                $user->assignRole($data['role']);
            }

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'update',
                'model_type' => User::class,
              //  'model_id' => $id,
                'old_values' => $oldValues,
                'new_values' => $data,
            ]);

            DB::commit();

            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteUser(int $id): bool
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->findOrFail($id);

            $deleted = $this->userRepository->delete($id);

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'delete',
                'model_type' => User::class,
             //   'model_id' => $id,
                'old_values' => $user->toArray(),
            ]);

            DB::commit();

            return $deleted;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function restoreUser(int $id): bool
    {
        DB::beginTransaction();

        try {
            $restored = $this->userRepository->restore($id);

            // Log activity
            $this->activityLogRepository->log([
                'action' => 'restore',
                'model_type' => User::class,
              //  'model_id' => $id,
            ]);

            DB::commit();

            return $restored;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function assignRole(int $userId, string $role): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        $user->assignRole($role);

        // Log activity
        $this->activityLogRepository->log([
            'action' => 'assign_role',
            'model_type' => User::class,
          //  'model_id' => $userId,
            'new_values' => ['role' => $role],
        ]);

        return true;
    }

    public function removeRole(int $userId, string $role): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        $user->removeRole($role);

        // Log activity
        $this->activityLogRepository->log([
            'action' => 'remove_role',
            'model_type' => User::class,
          //  'model_id' => $userId,
            'old_values' => ['role' => $role],
        ]);

        return true;
    }

    public function getUserPermissions(int $userId): array
    {
        $user = $this->userRepository->findOrFail($userId);
        return $user->getPermissions()->pluck('name')->toArray();
    }

    public function changePassword(int $userId, string $newPassword): bool
    {
        return $this->userRepository->update($userId, [
            'password' => Hash::make($newPassword)
        ]);
    }
}
