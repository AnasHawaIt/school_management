<?php

namespace Modules\Core\Contracts\Services;

use Modules\Core\Entities\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(array $filters = []): LengthAwarePaginator;

    public function getUser(int $id): ?User;

    public function createUser(array $data): User;

    public function updateUser(int $id, array $data): bool;

    public function deleteUser(int $id): bool;

    public function restoreUser(int $id): bool;

    public function assignRole(int $userId, string $role): bool;

    public function removeRole(int $userId, string $role): bool;

    public function getUserPermissions(int $userId): array;

    public function changePassword(int $userId, string $newPassword): bool;
}
