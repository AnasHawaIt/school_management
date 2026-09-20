<?php

namespace Modules\Core\app\Contracts\Services;

use Modules\Core\app\Entities\User;

interface AuthServiceInterface
{
    public function login(array $credentials): array;

    public function logout(): bool;

    public function me(): User;


    public function refreshToken(): string;
}
